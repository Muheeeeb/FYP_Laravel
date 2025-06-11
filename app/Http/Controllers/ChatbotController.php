<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Orhanerday\OpenAi\OpenAi;

class ChatbotController extends Controller
{
    private $campusInfo = "
        SZABIST has two main campuses:
        
        1. Islamabad Campus
        2. Karachi Campus
        
        Please specify which campus you're interested in.
    ";

    private $islamabadInfo = "
        Islamabad Campus Department Information:

        - Head of campus (HOC): Mr. Khusro Pervaiz    
        - DEAN: Dr. Muhammad Usman
        
        - Robotics and AI Department:
          HOD: Dr. Muhammad Imran
          Project Manager: Mr. Saad Irfan
          Location: H-8/4 Campus
          
        - Computer Science Department:
          HOD: Dr. Tazeen
          Project Manager: Dr. Shahzad Latif
          Location: H-8/4 Campus
          
        - Software Engineering:
          Project Manager: Dr. Babar Jehangir
          Location: H-8/4 Campus
          
        Contact Information:
        - Address: Street 9, Plot 67, Sector H-8/4, Islamabad
        - Phone: +92-51-4863363-65
        - Email: info@szabist-isb.edu.pk
    ";

    private $karachiInfo = "
        Karachi Campus Department Information:
        
        - Computer Science Department:
          HOD: Dr. Jawwad Shamsi
          Location: Main Campus
        
        - Software Engineering:
          HOD: Dr. Imran Khan
          Location: Main Campus
        
        - Artificial Intelligence:
          HOD: Dr. Asad Raza
          Location: Main Campus
          
        Contact Information:
        - Address: 90 & 100 Clifton, Block 5, Karachi
        - Phone: +92-21-111-922-478
        - Email: info@szabist.edu.pk
    ";

    private $jobRequirements = "
        General Job Requirements at SZABIST:

        Faculty Positions:
        1. Professor:
           - PhD in relevant field
           - Minimum 10 years teaching/research experience
           - HEC approved publications
        
        2. Associate Professor:
           - PhD in relevant field
           - 5 years post-PhD teaching/research experience
           - HEC approved publications
        
        3. Assistant Professor:
           - PhD/MS/MPhil in relevant field
           - 2-3 years teaching experience preferred
           - Research publications are a plus
        
        Lab Staff Requirements:
        - Bachelor's degree in relevant field
        - 2+ years lab management experience
        - Strong technical skills
        - Hardware/software troubleshooting expertise
        
        Administrative Positions:
        - Bachelor's degree minimum
        - 3+ years relevant experience
        - Strong communication skills
        - MS Office proficiency
        
        Required Documents for All Positions:
        1. Updated CV/Resume
        2. All educational documents and transcripts
        3. Experience certificates
        4. CNIC copy
        5. Recent passport size photographs
        6. HEC equivalence (for foreign degrees)
        7. Publications list (for faculty positions)
        
        Note: 
        - Specific requirements may vary by position and department
        - All degrees must be HEC recognized
        - Teaching experience should be from recognized institutions
    ";

    private $applicationInfo = "
        Application Process and Timeline:

        1. Initial Application (1-2 days):
           - Submit online application
           - Upload required documents
           - Receive confirmation email
        
        2. Application Review (3-5 working days):
           - Document verification
           - Eligibility checking
           - Initial screening
        
        3. Interview Process (1-2 weeks):
           - HR interview
           - Technical interview
           - Demo lecture (for faculty)
           - Department head meeting
        
        4. Final Selection (2-3 days):
           - Reference checks
           - Offer letter preparation
           - Contract signing
        
        Total Timeline: 2-3 weeks average
        
        Track Your Application:
        1. Visit careers.szabist.edu.pk
        2. Click 'Track Application'
        3. Enter your:
           - Registered email
           - Phone number
        4. View real-time status updates
        
        Application Status Categories:
        - Submitted: Application received
        - Under Review: Being evaluated
        - Shortlisted: Selected for interview
        - Interview Scheduled: Awaiting interview
        - Selected: Successful application
        - On Hold: Decision pending
        - Closed: Position filled
        
        Important Notes:
        - You will receive email notifications for all status changes
        - Keep your contact information updated
        - Check spam folder for emails
        - Contact HR if no update received within 5 working days
    ";

    private $benefitsInfo = "
        SZABIST Employee Benefits:

        1. Financial Benefits:
           - Competitive salary package
           - Annual increments
           - Performance bonuses
           - Medical allowance
           - Transport allowance
        
        2. Leave Benefits:
           - Annual paid leaves
           - Casual leaves
           - Sick leaves
           - Maternity/Paternity leave
        
        3. Professional Development:
           - Conference funding
           - Research grants
           - Training opportunities
           - Higher education support
        
        4. Other Benefits:
           - Health insurance
           - Provident fund
           - Gratuity
           - Flexible working hours
           - On-campus facilities access
    ";

    public function sendMessage(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'message' => 'required|string|max:1000',
            ]);

            // Get API key
            $apiKey = env('OPENAI_API_KEY');
            if (empty($apiKey)) {
                \Log::error('ChatBot Error: OpenAI API key not found');
                return response()->json([
                    'error' => true,
                    'message' => 'Configuration error. Please contact support.'
                ], 500);
            }

            // Initialize OpenAI
            $open_ai = new OpenAi($apiKey);

            // Log the request
            \Log::info('ChatBot: Sending request to OpenAI', [
                'message' => $validated['message']
            ]);

            // Make API call
            $result = $open_ai->chat([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are SZABIST\'s hiring platform assistant. Be professional and helpful.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $validated['message']
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 150
            ]);

            // Log the response
            \Log::info('ChatBot: Received response', [
                'raw_response' => $result
            ]);

            // Parse response
            $response = json_decode($result, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Failed to parse OpenAI response: ' . json_last_error_msg());
            }

            // Check for API errors
            if (isset($response['error'])) {
                throw new \Exception($response['error']['message'] ?? 'OpenAI API error');
            }

            // Check for missing response
            if (!isset($response['choices'][0]['message']['content'])) {
                throw new \Exception('Unexpected response format from OpenAI');
            }

            // Return success response
            return response()->json([
                'message' => $response['choices'][0]['message']['content']
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('ChatBot: Validation error', [
                'errors' => $e->errors()
            ]);
            return response()->json([
                'error' => true,
                'message' => 'Please provide a valid message.'
            ], 422);

        } catch (\Exception $e) {
            \Log::error('ChatBot Error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => true,
                'message' => 'An error occurred while processing your request. Please try again.'
            ], 500);
        }
    }
}