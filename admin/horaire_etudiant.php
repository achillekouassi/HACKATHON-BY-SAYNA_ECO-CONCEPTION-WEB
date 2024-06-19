<?php include 'layout/header.php'; ?>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            font-size: 2em;
            margin-bottom: 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr); /* 5 colonnes pour les jours de la semaine */
            gap: 10px;
        }

        .cell {
            border: 1px solid #ddd;
            padding: 20px;
            position: relative;
            background-color: #fafafa;
            border-radius: 5px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .cell:hover {
            background-color: #f0f0f0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .details {
            border: 1px solid #ddd;
            padding: 10px;
            margin-top: 10px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .details p {
            margin: 0;
            padding: 5px 0;
        }

        .details p:hover {
            text-decoration: underline;
            cursor: pointer;
        }

        .cell strong {
            display: block;
            margin-bottom: 10px;
            font-size: 1.2em;
            color: #555;
        }

        .cell span {
            display: block;
            font-size: 0.9em;
            color: #777;
        }

        .module {
            font-weight: bold;
            color: #333;
        }

        @media (max-width: 768px) {
            .grid {
                grid-template-columns: repeat(2, 1fr); /* 2 colonnes pour les appareils plus petits */
            }
        }

        @media (max-width: 480px) {
            .grid {
                grid-template-columns: 1fr; /* 1 colonne pour les appareils très petits */
            }
        }
    </style>

    <div class="container">
    <h5 class="modal-title" id="exampleModalLabel">Calendrier des cours</h5>
        <div class="grid">
            <?php
            include 'db.php'; // Assurez-vous que votre fichier db.php contient la connexion PDO

            $jours = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi"];
            $plage_horaire = "7h à 17h";

            $sql = "SELECT jour, heure_debut, heure_fin, modules.name AS nom_module, 
                           CONCAT(users.username, ' ', users.prenom) AS nom_professeur, 
                           emploi_du_temps.google_meet 
                    FROM emploi_du_temps
                    JOIN modules ON emploi_du_temps.module_id = modules.id
                    JOIN users ON emploi_du_temps.user_id = users.id
                    WHERE jour = ?";

            foreach ($jours as $jour) {
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$jour]);
                $cours = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo "<div class='cell' data-jour='$jour'>";
                echo "<strong>$jour</strong>";
         
                if (!empty($cours)) {
                    echo "<div class='details'>";
                    foreach ($cours as $cours) {
                        $link = $cours['google_meet'];
                        echo "<p data-link='$link'><span class='module'>{$cours['nom_module']}</span>: {$cours['heure_debut']} - {$cours['heure_fin']} ({$cours['nom_professeur']})</p>";
                    }
                    echo "</div>";
                } else {
                    echo "<div class='details'><p>Pas de module programmé</p></div>";
                }
                
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const events = document.querySelectorAll('.details p');
            events.forEach(event => {
                event.addEventListener('click', () => {
                    let link = event.getAttribute('data-link');
                    if (link) {
                        if (!link.startsWith('http://') && !link.startsWith('https://')) {
                            link = 'https://' + link;
                        }
                        window.open(link, '_blank');
                    }
                });
            });
        });
    </script>
</body>
</html>
