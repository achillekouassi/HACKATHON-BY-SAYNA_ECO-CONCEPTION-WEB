<?php include 'layout/header.php'; ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80vw;
            margin: auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            padding: 20px;
        }

        .calendar {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            margin-top: 20px;
        }

        .day {
            border: 1px solid #ccc;
            padding: 15px;
            cursor: pointer;
            text-align: center;
            background-color: #f9f9f9;
            transition: background-color 0.3s ease;
            position: relative;
        }

        .day:hover {
            background-color: #e0e0e0;
        }

        .grid-container {
            margin-top: 10px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 10px;
        }

        .event {
            border: 1px solid #ccc;
            padding: 10px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            cursor: pointer;
        }

        .event p {
            margin: 5px 0;
        }

        /* Media Queries pour le Responsive Design */
        @media (max-width: 768px) {
            .calendar {
                grid-template-columns: repeat(2, 1fr);
            }

            .day {
                padding: 10px;
            }

            .grid {
                grid-template-columns: repeat(1, 1fr);
            }

            .event {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h5 class="modal-title" id="exampleModalLabel">Calendrier des Cours</h5>
        <div class="calendar">
            <?php
            include 'db.php';

            $jours = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi"];

            $sql = "SELECT jour, heure_debut, heure_fin, 
                           modules.name AS nom_module, 
                           CONCAT(users.username, ' ', users.prenom) AS nom_professeur, 
                           classes.name AS nom_classe,
                           emploi_du_temps.date_debut,
                           emploi_du_temps.date_fin,
                           emploi_du_temps.google_meet
                    FROM emploi_du_temps
                    JOIN modules ON emploi_du_temps.module_id = modules.id
                    JOIN users ON emploi_du_temps.user_id = users.id
                    JOIN classes ON emploi_du_temps.classe_id = classes.id
                    WHERE emploi_du_temps.jour = ?";

            foreach ($jours as $jour) {
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$jour]);
                $cours = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo "<div class='day' data-jour='$jour'>$jour";

                if (!empty($cours)) {
                    foreach ($cours as $cours) {
                        echo "<div class='grid-container'>";
                        echo "<div class='grid'>";
                        echo "<div class='event' data-link='{$cours['google_meet']}'>";
                        echo "<p><strong>{$cours['nom_module']}</strong><br>{$cours['nom_classe']}<br> {$cours['jour']}<br>{$cours['date_debut']} - {$cours['date_fin']}<br> du {$cours['heure_debut']} - {$cours['heure_fin']}<br><em>{$cours['nom_professeur']}</em></p>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>Pas de cours ce jour-là.</p>";
                }

                echo "</div>";
            }
            ?>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const events = document.querySelectorAll('.event');

            events.forEach(event => {
                event.addEventListener('click', () => {
                    let link = event.getAttribute('data-link');
                    if (link) {
                        // Ajouter le protocole si manquant
                        if (!link.startsWith('http://') && !link.startsWith('https://')) {
                            link = 'https://' + link;
                        }
                        window.open(link, '_blank');
                    }
                });
            });
        });
    </script>
<?php
include 'layout/footer.php';
?>
