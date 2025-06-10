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
        $request->headers->set('Accept', 'application/json');

        try {
            if (!env('OPENAI_API_KEY')) {
                throw new \Exception('OpenAI API key is not set');
            }

            $open_ai = new OpenAi(env('OPENAI_API_KEY'));
            
            $systemPrompt = "You are SZABIST's hiring platform assistant. Follow these rules:

            1. Always start by identifying which campus the user is interested in if not specified.
            
            2. For campus-specific queries:
               - Islamabad Campus: {$this->islamabadInfo}
               - Karachi Campus: {$this->karachiInfo}
            
            3. For job requirements: {$this->jobRequirements}
            
            4. For application process: {$this->applicationInfo}
            
            5. For benefits information: {$this->benefitsInfo}
            
            Key Points to Always Remember:
            - Recruitment typically takes 2-3 weeks
            - Applications can be tracked online using email and phone number
            - All degrees must be HEC recognized
            - Document requirements are strict and must be complete
            
            Response Guidelines:
            1. Be professional yet friendly
            2. Provide specific, accurate information
            3. Mention application tracking when discussing process
            4. Include timeline information when relevant
            5. Suggest visiting the official website for current openings
            6. Ask for clarification if the query is unclear
            
            If asked about salary:
            - Explain that it varies by position and experience
            - Direct them to HR for specific details
            - Mention the additional benefits package
            
            For technical position queries:
            - Emphasize required qualifications
            - Mention any mandatory certifications
            - Explain the technical interview process";

            $result = $open_ai->chat([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $systemPrompt
                    ],
                    [
                        'role' => 'user',
                        'content' => $request->message
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 800
            ]);

            $response = json_decode($result, true);

            if (isset($response['choices'][0]['message']['content'])) {
                return response()->json([
                    'message' => $response['choices'][0]['message']['content']
                ]);
            } else {
                throw new \Exception('Unexpected response format from OpenAI');
            }

        } catch (\Exception $e) {
            \Log::error('ChatBot Error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Sorry, I encountered an error. Please try again later. If the problem persists, please contact our HR department at hr@szabist.edu.pk'
            ], 500);
        }
    }
}