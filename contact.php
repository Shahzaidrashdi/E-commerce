<?php
include('config.php');
$page_title = "Contact Us - E-Commerce website";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .contact-container {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin: 40px 0;
        }
        .contact-card {
            flex: 1;
            min-width: 300px;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .contact-card h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            border-bottom: 2px solid #f1c40f;
            padding-bottom: 8px;
        }
        .contact-info {
            margin-bottom: 15px;
        }
        .contact-info i {
            margin-right: 10px;
            color: #f1c40f;
        }
        .map-container {
            height: 300px;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
        }
        .contact-form {
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Contact Us</h1>
        <p>Have questions or need assistance? Reach out to our team or visit one of our branches.</p>
        
        <div class="contact-container">
            <!-- Head Office -->
            <div class="contact-card">
                <h3>Head Office</h3>
                <div class="contact-info">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>123 Shopping Avenue, Downtown, New York, NY 10001</span>
                </div>
                <div class="contact-info">
                    <i class="fas fa-phone"></i>
                    <span>+1 (212) 555-1234</span>
                </div>
                <div class="contact-info">
                    <i class="fas fa-envelope"></i>
                    <span>support@youronlineshop.com</span>
                </div>
                <div class="contact-info">
                    <i class="fas fa-clock"></i>
                    <span>Mon-Fri: 9:00 AM - 8:00 PM<br>Sat-Sun: 10:00 AM - 6:00 PM</span>
                </div>
                <div class="map-container">
                    <iframe src="https://maps.google.com/maps?q=123+Shopping+Avenue,+New+York&output=embed" width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>
            </div>
            
            <!-- West Coast Branch -->
            <div class="contact-card">
                <h3>West Coast Branch</h3>
                <div class="contact-info">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>456 Commerce Street, Los Angeles, CA 90015</span>
                </div>
                <div class="contact-info">
                    <i class="fas fa-phone"></i>
                    <span>+1 (213) 555-5678</span>
                </div>
                <div class="contact-info">
                    <i class="fas fa-envelope"></i>
                    <span>west@youronlineshop.com</span>
                </div>
                <div class="contact-info">
                    <i class="fas fa-clock"></i>
                    <span>Mon-Fri: 8:00 AM - 7:00 PM<br>Sat-Sun: 9:00 AM - 5:00 PM</span>
                </div>
                <div class="map-container">
                    <iframe src="https://maps.google.com/maps?q=456+Commerce+Street,+Los+Angeles&output=embed" width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>
            </div>
        </div>
        
        <!-- Contact Form -->
        <div class="contact-form">
            <h2>Send Us a Message</h2>
            <form action="process_contact.php" method="POST">
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" required>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn-primary">Send Message</button>
            </form>
        </div>
        
        <!-- Customer Support Info -->
        <div class="support-info" style="margin-top: 40px; background: #f8f9fa; padding: 20px; border-radius: 8px;">
            <h3>Customer Support</h3>
            <p>For immediate assistance, please call our 24/7 customer support hotline:</p>
            <p style="font-size: 1.2em; font-weight: bold; color: #e74c3c;">+1 (800) 555-9999</p>
            <p>Or email us at: <a href="mailto:support@youronlineshop.com">support@youronlineshop.com</a></p>
        </div>
    </div>
    
    <!-- Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>