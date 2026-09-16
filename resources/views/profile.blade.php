<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Profile</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            min-height: 100vh;

            display: flex;
            justify-content: center;
            align-items: center;
        }

        .profile-card {
            width: 350px;
            background-color: white;
            padding: 35px 30px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 25px;
            border: 5px solid #ddd;
        }

        .profile-data {
            margin-bottom: 12px;
            padding: 12px;
            background-color: #e0e0e0;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="profile-card">

        <img src="{{ asset('profile.jpg') }}"
             alt="Foto Profile"
             class="profile-image">

        <div class="profile-data">
            {{ $nama }}
        </div>

        <div class="profile-data">
            {{ $kelas }}
        </div>

        <div class="profile-data">
            {{ $npm }}
        </div>

    </div>

</body>
</html>
