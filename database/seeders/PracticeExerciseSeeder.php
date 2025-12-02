<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Practice;
use App\Models\PracticeExercise;

class PracticeExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first practice (CSS Animation practice)
        $cssAnimationPractice = Practice::where('slug', 'animate-images-with-css-keyframes')->first();
        
        if ($cssAnimationPractice) {
            PracticeExercise::create([
                'practice_id' => $cssAnimationPractice->id,
                'title' => 'Basic Fade Animation',
                'description' => 'Create a simple fade-in animation using CSS keyframes',
                'instructions' => "1. Create a @keyframes rule named 'fadeIn'\n2. Set opacity from 0 to 1\n3. Apply the animation to an element\n4. Set animation duration to 2 seconds",
                'starter_code' => "@keyframes fadeIn {\n  /* Add your keyframes here */\n}\n\n.fade-element {\n  /* Apply animation here */\n}",
                'solution_code' => "@keyframes fadeIn {\n  from { opacity: 0; }\n  to { opacity: 1; }\n}\n\n.fade-element {\n  animation: fadeIn 2s ease-in;\n}",
                'hints' => "- Use 'from' and 'to' keywords in keyframes\n- The animation property takes: name, duration, timing-function\n- Don't forget to set initial opacity",
                'order' => 1,
                'difficulty' => 'easy',
                'is_completed' => false,
            ]);

            PracticeExercise::create([
                'practice_id' => $cssAnimationPractice->id,
                'title' => 'Slide and Scale',
                'description' => 'Combine multiple transformations in a single animation',
                'instructions' => "1. Create a keyframe animation that slides an element from left\n2. Add a scale transformation\n3. Use percentage-based keyframes (0%, 50%, 100%)\n4. Make it smooth with ease-in-out timing",
                'starter_code' => "@keyframes slideScale {\n  /* Add your keyframes here */\n}\n\n.animated-box {\n  /* Apply animation here */\n}",
                'solution_code' => "@keyframes slideScale {\n  0% {\n    transform: translateX(-100px) scale(0.5);\n  }\n  50% {\n    transform: translateX(0) scale(1.2);\n  }\n  100% {\n    transform: translateX(0) scale(1);\n  }\n}\n\n.animated-box {\n  animation: slideScale 1.5s ease-in-out;\n}",
                'hints' => "- Use transform property for both translate and scale\n- Combine transformations in one line\n- 50% keyframe creates a bounce effect",
                'order' => 2,
                'difficulty' => 'medium',
                'is_completed' => false,
            ]);

            PracticeExercise::create([
                'practice_id' => $cssAnimationPractice->id,
                'title' => 'Infinite Rotation',
                'description' => 'Create a continuously rotating element',
                'instructions' => "1. Create a rotation animation using transform: rotate()\n2. Make it infinite using animation-iteration-count\n3. Use linear timing for smooth rotation\n4. Set appropriate duration",
                'starter_code' => "@keyframes rotate {\n  /* Add your keyframes here */\n}\n\n.spinner {\n  /* Apply animation here */\n}",
                'solution_code' => "@keyframes rotate {\n  from {\n    transform: rotate(0deg);\n  }\n  to {\n    transform: rotate(360deg);\n  }\n}\n\n.spinner {\n  animation: rotate 2s linear infinite;\n}",
                'hints' => "- Rotate from 0deg to 360deg\n- Use 'infinite' keyword for endless animation\n- Linear timing prevents speed variations",
                'order' => 3,
                'difficulty' => 'easy',
                'is_completed' => false,
            ]);

            PracticeExercise::create([
                'practice_id' => $cssAnimationPractice->id,
                'title' => 'Complex Multi-Step Animation',
                'description' => 'Create an advanced animation with multiple stages',
                'instructions' => "1. Create a 5-stage animation (0%, 25%, 50%, 75%, 100%)\n2. Combine opacity, transform, and color changes\n3. Add animation-delay for staggered effect\n4. Use animation-fill-mode to maintain final state",
                'starter_code' => "@keyframes complex {\n  /* Add your keyframes here */\n}\n\n.complex-element {\n  /* Apply animation here */\n}",
                'solution_code' => "@keyframes complex {\n  0% {\n    opacity: 0;\n    transform: translateY(-50px) rotate(0deg);\n    background-color: #3b82f6;\n  }\n  25% {\n    opacity: 0.5;\n    transform: translateY(-25px) rotate(90deg);\n  }\n  50% {\n    opacity: 1;\n    transform: translateY(0) rotate(180deg);\n    background-color: #8b5cf6;\n  }\n  75% {\n    transform: translateY(25px) rotate(270deg);\n  }\n  100% {\n    transform: translateY(0) rotate(360deg);\n    background-color: #ec4899;\n  }\n}\n\n.complex-element {\n  animation: complex 3s ease-in-out 0.5s forwards;\n}",
                'hints' => "- Break animation into clear stages\n- Use forwards to keep final state\n- Delay helps with timing\n- Combine multiple properties for rich effects",
                'order' => 4,
                'difficulty' => 'hard',
                'is_completed' => false,
            ]);
        }

        // Get Python practice
        $pythonPractice = Practice::where('category', 'Python')->first();
        
        if ($pythonPractice) {
            PracticeExercise::create([
                'practice_id' => $pythonPractice->id,
                'title' => 'Hello World',
                'description' => 'Write your first Python program',
                'instructions' => "1. Use the print() function\n2. Print 'Hello, World!' to the console\n3. Run the program",
                'starter_code' => "# Write your code here\n",
                'solution_code' => "print('Hello, World!')",
                'hints' => "- Use print() function\n- Strings can use single or double quotes",
                'order' => 1,
                'difficulty' => 'easy',
                'is_completed' => false,
            ]);

            PracticeExercise::create([
                'practice_id' => $pythonPractice->id,
                'title' => 'Variables and Data Types',
                'description' => 'Learn to work with different data types',
                'instructions' => "1. Create a string variable with your name\n2. Create an integer variable with your age\n3. Create a boolean variable\n4. Print all variables",
                'starter_code' => "# Create your variables here\nname = \nage = \nis_student = \n\n# Print them\n",
                'solution_code' => "name = 'John Doe'\nage = 25\nis_student = True\n\nprint(f'Name: {name}')\nprint(f'Age: {age}')\nprint(f'Is Student: {is_student}')",
                'hints' => "- Use quotes for strings\n- Numbers don't need quotes\n- Boolean values are True or False (capitalized)\n- Use f-strings for formatted output",
                'order' => 2,
                'difficulty' => 'easy',
                'is_completed' => false,
            ]);
        }
    }
}
