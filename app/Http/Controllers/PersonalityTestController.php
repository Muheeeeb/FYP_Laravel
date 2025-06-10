<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersonalityQuestion;
use App\Models\PersonalityTest;
use App\Models\PersonalityAnswer;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Log;

class PersonalityTestController extends Controller
{
    /**
     * Display the personality test form
     */
    public function showTest($applicationId)
    {
        $application = JobApplication::findOrFail($applicationId);
        
        // Check if test already taken
        if ($application->personalityTest) {
            return redirect()->route('jobs.apply.success')
                ->with('info', 'You have already completed the personality test for this application.');
        }
        
        // Get all active questions
        $questions = PersonalityQuestion::where('is_active', true)
            ->orderBy('order')
            ->get();
            
        return view('jobs.personality-test', compact('application', 'questions'));
    }
    
    /**
     * Process the submitted personality test
     */
    public function submitTest(Request $request, $applicationId)
    {
        $application = JobApplication::findOrFail($applicationId);
        
        // Check if test already taken
        if ($application->personalityTest) {
            return redirect()->route('jobs.apply.success')
                ->with('info', 'You have already completed the personality test for this application.');
        }
        
        // Validate input
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required'
        ]);
        
        try {
            // Create personality test record
            $personalityTest = PersonalityTest::create([
                'job_application_id' => $application->id
            ]);
            
            $traits = [
                'teamwork' => 0,
                'problem_solving' => 0,
                'risk_taking' => 0,
                'adaptability' => 0,
                'structure' => 0,
                'leadership' => 0,
                'stress_management' => 0,
                'innovation' => 0,
                'attention_to_detail' => 0,
                'communication' => 0
            ];
            
            $traitScores = [
                'teamwork' => 0,
                'problem_solving' => 0,
                'risk_taking' => 0,
                'adaptability' => 0,
                'structure' => 0,
                'leadership' => 0,
                'stress_management' => 0,
                'innovation' => 0,
                'attention_to_detail' => 0,
                'communication' => 0
            ];
            
            $traitQuestionCount = [
                'teamwork' => 0,
                'problem_solving' => 0,
                'risk_taking' => 0,
                'adaptability' => 0,
                'structure' => 0,
                'leadership' => 0,
                'stress_management' => 0,
                'innovation' => 0,
                'attention_to_detail' => 0,
                'communication' => 0
            ];
            
            // Define which questions map to which traits
            $questionTraitMap = [
                1 => 'teamwork',
                2 => 'problem_solving',
                3 => 'risk_taking',
                4 => 'adaptability',
                5 => 'structure',
                6 => 'leadership',
                7 => 'stress_management',
                8 => 'innovation',
                9 => 'attention_to_detail',
                10 => 'communication',
                11 => ['problem_solving', 'adaptability'],
                12 => ['teamwork', 'structure']
            ];
            
            // Store individual answers
            foreach ($request->answers as $questionId => $answer) {
                $question = PersonalityQuestion::find($questionId);
                
                if (!$question) {
                    continue;
                }
                
                // Store answer
                $score = null;
                if ($question->type === 'likert_scale') {
                    $score = (int) $answer;
                }
                
                PersonalityAnswer::create([
                    'personality_test_id' => $personalityTest->id,
                    'question_id' => $questionId,
                    'answer' => is_array($answer) ? json_encode($answer) : $answer,
                    'score' => $score
                ]);
                
                // Calculate trait scores
                if (isset($questionTraitMap[$questionId])) {
                    $traits = $questionTraitMap[$questionId];
                    
                    if (!is_array($traits)) {
                        $traits = [$traits];
                    }
                    
                    foreach ($traits as $trait) {
                        if ($question->type === 'likert_scale') {
                            $traitScores[$trait] += $score;
                            $traitQuestionCount[$trait]++;
                        } else if ($question->type === 'multiple_choice') {
                            // For multiple choice, we use the index as a basis for scoring
                            $options = json_decode($question->options, true);
                            $answerIndex = array_search($answer, $options);
                            
                            if ($answerIndex !== false) {
                                $traitScores[$trait] += ($answerIndex + 1);
                                $traitQuestionCount[$trait]++;
                            }
                        }
                    }
                }
            }
            
            // Calculate final trait scores (as percentages)
            foreach ($traits as $trait => $score) {
                if ($traitQuestionCount[$trait] > 0) {
                    $maxScore = $traitQuestionCount[$trait] * 5; // Max score is 5 per question
                    $traits[$trait] = round(($traitScores[$trait] / $maxScore) * 100);
                }
            }
            
            // Generate summary based on highest traits
            $highestTraits = array_keys($traits, max($traits));
            $lowestTraits = array_keys($traits, min($traits));
            
            $summaryTexts = [
                'teamwork' => 'You value collaboration and working with others.',
                'problem_solving' => 'You excel at analytical thinking and addressing complex issues.',
                'risk_taking' => 'You are comfortable with uncertainty and taking calculated risks.',
                'adaptability' => 'You adapt well to changing circumstances and new environments.',
                'structure' => 'You appreciate clear guidelines and organized workflows.',
                'leadership' => 'You demonstrate strong leadership qualities and taking initiative.',
                'stress_management' => 'You handle pressure well and maintain composure in challenging situations.',
                'innovation' => 'You value creative thinking and novel approaches to problems.',
                'attention_to_detail' => 'You are thorough and pay close attention to the finer points.',
                'communication' => 'You effectively express your ideas and listen to others.'
            ];
            
            $summary = "Personality Profile:\n\n";
            
            // Add highest traits
            $summary .= "Your strongest characteristics: ";
            foreach ($highestTraits as $trait) {
                $summary .= $summaryTexts[$trait] . " ";
            }
            
            // Add lowest traits (framed positively)
            $summary .= "\n\nAreas for growth: ";
            foreach ($lowestTraits as $trait) {
                $summary .= "Consider developing your " . str_replace('You ', '', strtolower($summaryTexts[$trait])) . " ";
            }
            
            // Update the personality test with results and summary
            $personalityTest->update([
                'results' => $traits,
                'summary' => $summary
            ]);
            
            return redirect()->route('jobs.apply.success')
                ->with('success', 'Your personality test has been submitted successfully.');
            
        } catch (\Exception $e) {
            Log::error('Error saving personality test: ' . $e->getMessage());
            return back()->with('error', 'There was an error submitting your personality test. Please try again.')
                ->withInput();
        }
    }
}
