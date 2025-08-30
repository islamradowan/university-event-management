<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Announcement</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background: #dc2626; color: white; padding: 20px; text-align: center;">
            <h1>Important Announcement</h1>
        </div>
        
        <div style="padding: 20px; background: #f9f9f9;">
            <h2>Hello {{ $userName }},</h2>
            
            <div style="background: #fef2f2; border: 1px solid #fecaca; padding: 15px; border-radius: 5px;">
                <h3>{{ $announcementTitle }}</h3>
                <p>{{ $announcementMessage }}</p>
            </div>
            
            <p>Please take note of this important information from the administration.</p>
        </div>
        
        <div style="padding: 20px; text-align: center; color: #666; font-size: 12px;">
            <p>University Event Management System</p>
        </div>
    </div>
</body>
</html>