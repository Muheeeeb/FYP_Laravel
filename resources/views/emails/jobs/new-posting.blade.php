<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #006dd7;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
            background: #f8f9fa;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background: #006dd7;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .job-details {
            margin-top: 15px;
            padding: 15px;
            background: #fff;
            border-radius: 5px;
            border-left: 4px solid #006dd7;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Job Opportunity Available!</h1>
        </div>
        
        <div class="content">
            <p>Dear Subscriber,</p>
            
            <p>We're excited to inform you that a new job opportunity has been posted on HireSmart.</p>
            
            <div class="job-details">
                <h2>{{ $title }}</h2>
                @if(isset($department) && $department)
                <p><strong>Department:</strong> {{ $department }}</p>
                @endif
                
                <h3>Description:</h3>
                <p>{{ $description }}</p>
                
                @if(isset($requirements) && $requirements)
                <h3>Requirements:</h3>
                <p>{{ $requirements }}</p>
                @endif
            </div>
            
            <p>Visit our website to view the details and apply!</p>
            
            <a href="{{ $url }}" class="button">View Job Posting</a>
            
            <p style="margin-top: 20px;">
                Best regards,<br>
                HireSmart Team
            </p>
            
            <p style="font-size: 12px; color: #666; margin-top: 30px;">
                If you no longer wish to receive these emails, you can <a href="{{ url('/unsubscribe/' . urlencode(base64_encode($subscriber->email ?? ''))) }}">unsubscribe here</a>.
            </p>
        </div>
    </div>
</body>
</html>