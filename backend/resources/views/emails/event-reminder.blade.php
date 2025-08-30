<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Event Reminder</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f59e0b; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .footer { padding: 20px; text-align: center; color: #666; font-size: 12px; }
        .highlight { background: #fef3c7; padding: 15px; border-left: 4px solid #f59e0b; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Event Reminder</h1>
        </div>
        
        <div class="content">
            <h2>Hello {{ $user->name }},</h2>
            
            <div class="highlight">
                <p><strong>Don't forget!</strong> You have an event tomorrow:</p>
            </div>
            
            <h3>{{ $event->title }}</h3>
            <p><strong>Date:</strong> {{ $event->start_at->format('F j, Y') }}</p>
            <p><strong>Time:</strong> {{ $event->start_at->format('g:i A') }}</p>
            <p><strong>Location:</strong> {{ $event->location ?? 'TBA' }}</p>
            
            <p>Make sure to:</p>
            <ul>
                <li>Arrive 15 minutes early</li>
                <li>Bring your QR code for check-in</li>
                <li>Check for any last-minute updates</li>
            </ul>
            
            <p>We're excited to see you there!</p>
        </div>
        
        <div class="footer">
            <p>University Event Management System</p>
            <p>This is an automated reminder, please do not reply.</p>
        </div>
    </div>
</body>
</html>