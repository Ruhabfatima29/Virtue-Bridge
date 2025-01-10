<?php include('../includes/adminNavBar.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Education Fundraisers in Pakistan</title>
    <link rel="stylesheet" href="../../assets/css/creatorDashboard.css">
    <style>
        /* Admin Container Styling */
        .admin-container {
            display: flex;
            flex-direction: column;
            margin-left: 220px; /* Adjust relative to navbar width */
            padding: 20px;
            background-color: #f5f5f5;
            min-height: 100vh;
        }

        /* Page Container */
        .fundraisers-container {
            margin: 20px auto;
            padding: 20px;
        }

        .fundraisers-container h1 {
            text-align: center;
            color: #007b7e;
            margin-bottom: 30px;
            font-size: 2.5rem;
            font-weight: bold;
        }
        .fundraisers-container h2 {
            text-align: center;
            color: #224c6d;
            margin-bottom: 30px;
            font-size: 1.1rem;
        }
        /* Card Grid */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
            justify-content: center;
        }

        /* Card Styling */
        .card {
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 20px;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        /* Logo Styling */
        .card img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            margin-bottom: 15px;
            border-radius: 10px;
            background-color: #f9fafc;
            padding: 10px;
        }

        /* Card Content */
        .card h2 {
            color: #224c6d;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 1rem;
            color: #555;
            margin-bottom: 15px;
        }

        .card a {
            display: inline-block;
            background-color: #224c6d;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .card a:hover {
            background-color: #003782;
        }
    </style>
</head>

<body>
    <div class="admin-container">
        <div class="fundraisers-container">
            <h1>Education Fundraisers in Pakistan</h1>
            <h2>Support these organizations to promote education in Pakistan. 
            <br>Collaborate with them, as they can be proved as a helping hand.</h2>
            <div class="card-grid">
                <!-- Card 1 -->
                <div class="card">
                    <img src="https://akhuwat.org.pk/wp-content/uploads/2022/09/Logo-EN-Black.png" alt="Akhuwat Foundation">
                    <h2>Akhuwat Foundation</h2>
                    <p>Supporting education through interest-free loans for students across Pakistan.</p>
                    <a href="https://www.akhuwat.org.pk/" target="_blank">Visit Website</a>
                </div>
                <!-- Card 2 -->
                <div class="card">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQX-bERzK3d2aLt0U7CbuPtdUlOouK8tJXszQ&s" alt="MALALA Fund">
                    <h2>Malala Fund</h2>
                    <p>Malala Fund is working for a world where every girl can learn and lead..</p>
                    <a href="https://malala.org/countries/pakistan/" target="_blank">Visit Website</a>
                </div>
                <!-- Card 3 -->
                <div class="card">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT7omRkp4Rt9gZOoQRjPIrVBQlrFQ0YQnZpAg&s" alt="Zindagi Trust">
                    <h2>Zindagi Trust</h2>
                    <p>Transforming education in government schools with innovative teaching methods.</p>
                    <a href="https://www.zindagitrust.org/" target="_blank">Visit Website</a>
                </div>
                <!-- Card 4 -->
                <div class="card">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTmbdQwYQLjVq49Y-JM4bVgrojDvjuRzkSB-A&s" alt="The Citizens Foundation">
                    <h2>The Citizens Foundation</h2>
                    <p>Building schools and providing quality education for underprivileged children in Pakistan.</p>
                    <a href="https://www.thecitizensfoundation.org/" target="_blank">Visit Website</a>
                </div>
                <!-- Card 5 -->
                <div class="card">
                    <img src="https://alifailaan.pk/wp-content/uploads/2014/05/Alif-Ailaan-logo.png" alt="Alif Ailaan">
                    <h2>Alif Ailaan</h2>
                    <p>Advocacy and campaigns to reform the education system and promote education rights.</p>
                    <a href="https://www.alifailaan.pk/" target="_blank">Visit Website</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
