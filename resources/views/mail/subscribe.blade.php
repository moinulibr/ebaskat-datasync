<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your discount voucher | eBaskat</title>
   <style>
        .header{
            margin: 30px;
        }
        .social{
            text-align: center;
        }
        .img{
            margin-left:8%;
            height:85%;
            width:85%;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="https://ebaskat.com/assets/images/logo.png" alt="eBaskat Logo">
        <br>
        <br>
        <em><b>Hello,</b> It is so nice to meet you!</em>
        <br>
        <p>
        We will get in touch with you again the day we open our
        virtual doors, so you can use your voucher and save. There
        will be other exciting offers on the site that day so you really
        don’t want to miss it.
        </p>
        <br>
        <img src="https://ebaskat-admin.s3.eu-west-1.amazonaws.com/banner/blIAhwKmFmSTW30HnzXDnmRZM0BG7rhfbwTbQi5z.png" alt="Your discount voucher" class="img">
        <br>
        <br>
        <div class="social">
            <h2>Join our social family</h2>
            <a href="https://www.facebook.com/ebaskatshopping"><img src="https://img.icons8.com/fluency/48/000000/facebook-new.png"/></a>
            <a href="https://twitter.com/EBaskat"><img src="https://img.icons8.com/fluency/48/000000/twitter.png"/></a>
            <a href="https://www.instagram.com/ebaskatshopping/"><img src="https://img.icons8.com/fluency/48/000000/instagram-new.png"/></a>
            <a href="https://www.pinterest.ie/ebaskat/"><img src="https://img.icons8.com/color/48/000000/pinterest--v1.png"/></a>
            <a href="https://www.snapchat.com/add/ebaskat"><img src="https://img.icons8.com/fluency/48/000000/snapchat.png"/></a>
            <a href="https://www.tiktok.com/@ebaskatshopping"><img src="https://img.icons8.com/color/48/000000/tiktok--v1.png"/></a>
        </div>
        <br>
        <p>The eBaskat Customer care Team</p>
        <b>eBaskat.com</b>
        <br>
        <br>
        <small class="social">This email was sent to {{ $details['email'] }} because you signed you up to receive news,
        product updates and tips from ebaskat.com..</small> <br>
        <b>If you don't want to receive such emails in the future, please <a href="http://admin.ebaskat.com/unsubscribe/{{ $details['email'] }}">unsubscribe here</a>.</b>
    <div>
</body>
</html>
