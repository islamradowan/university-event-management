<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Registration Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4f46e5; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .footer { padding: 20px; text-align: center; color: #666; font-size: 12px; }
        .button { background: #4f46e5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Registration Confirmed!</h1>
        </div>
        
        <div class="content">
            <h2>Hello {{ $user->name }},</h2>
            
            <p>You have successfully registered for the following event:</p>
            
            <h3>{{ $event->title }}</h3>
            <p><strong>Date:</strong> {{ $event->start_at->format('F j, Y') }}</p>
            <p><strong>Time:</strong> {{ $event->start_at->format('g:i A') }}</p>
            <p><strong>Location:</strong> {{ $event->location ?? 'TBA' }}</p>
            
            <p>{{ $event->description }}</p>
            
            <p>Please save this email as confirmation of your registration. You can view your QR code for check-in by visiting your "My Events" page.</p>
            
            <p>We look forward to seeing you at the event!</p>
        </div>
        
        <div class="footer">
            <p>University Event Management System</p>
            <p>This is an automated message, please do not reply.</p>
        </div>
    </div>
</body>
</html>