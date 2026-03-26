<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Enrollment;
use Midtrans\Config;
use Midtrans\Notification;

class CallbackController extends Controller
{
    public function midtransCallback(Request $request)
    {
        // 1. Konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

        try {
            // 2. Tangkap "surat" notifikasi dari Midtrans
            $notification = new Notification();

            $transactionStatus = $notification->transaction_status;
            $orderId = $notification->order_id;
            $fraudStatus = $notification->fraud_status;

            // 3. Cari data transaksi di database kita berdasarkan ID Transaksi (reference_number)
            $transaction = Transaction::where('reference_number', $orderId)->first();

            // Kalau transaksinya nggak ketemu, suruh Midtrans berhenti
            if (!$transaction) {
                return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
            }

            // 4. Cek status pembayarannya dan update di database kita
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $transaction->update(['status' => 'pending']);
                } else if ($fraudStatus == 'accept') {
                    $transaction->update(['status' => 'success']);
                }
            } else if ($transactionStatus == 'settlement') {
                // Pembayaran sukses (Gopay, Transfer Bank, Indomaret, dll)
                $transaction->update(['status' => 'success']);
            } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                $transaction->update(['status' => 'failed']);
            } else if ($transactionStatus == 'pending') {
                $transaction->update(['status' => 'pending']);
            }

            // =======================================================
            // 5. TINDAKAN KHUSUS JIKA TRANSAKSI SUKSES
            // =======================================================
            if ($transaction->status == 'success') {
                
                // SKENARIO A: Jika yang dibeli adalah Kelas (Course)
                if ($transaction->course_id) {
                    // Cek biar nggak kecetak dobel (kalau Midtrans ngirim notif 2 kali)
                    $exists = Enrollment::where('user_id', $transaction->user_id)
                                        ->where('course_id', $transaction->course_id)
                                        ->exists();
                    if (!$exists) {
                        Enrollment::create([
                            'user_id' => $transaction->user_id,
                            'course_id' => $transaction->course_id
                        ]);
                    }
                }

                // SKENARIO B: Jika yang dibeli adalah Practice (Freemium)
                // Kita TIDAK PERLU melakukan apa-apa lagi! 
                // Karena Guard di PracticeController kita cuma ngecek kolom 'status' = 'success' di tabel transactions.
                // Begitu statusnya berubah jadi success di atas, gembok otomatis hancur! 🔓
            }

            // Beri respon sukses ke Midtrans supaya mereka tau suratnya udah kita baca
            return response()->json(['message' => 'Callback berhasil diproses']);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}