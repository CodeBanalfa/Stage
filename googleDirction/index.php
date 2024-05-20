<?php
/*
Template Name: Page avec Carte et Formulaire
*/
?>

<?php 
// Inclusion du fichier wp-load.php pour charger WordPress
require( 'chemin/vers/votre/wp-load.php' );

// Vérification de l'existence de la fonction get_header()
if ( function_exists( 'get_header' ) ) {
    get_header();
} else {
    echo "Erreur: Fonction get_header() non trouvée. Assurez-vous que WordPress est correctement chargé.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte avec Formulaire</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Ajouter FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
    #map {
        width: 100%;
        height: 400px;
        margin: 20px auto;
    }
    </style>
</head>

<body>
    <div class="container">
        <div id="map"></div>
        <div class="jumbotron">
            <div class="mt-4">
                <form class="form-group" method="POST" action="">
                    <div class="row align-items-center">
                        <!-- Utiliser Bootstrap pour les champs de saisie avec des add-ons -->
                        <div class="input-group col-xs-4">
                            <label for="address" class="block">
                                <input type="text" id="address" placeholder="Votre adresse" name="address"
                                    autocomplete="address"
                                    class="block w-full px-3 py-2 mt-1 text-gray-700 border rounded-md hover:bg-indigo-500">
                            </label>
                            <button type="submit" name="submit" class="btn btn-primary">Entrer</button>
                        </div>
                    </div>
                </form>
            </div>
            <?php
            if (isset($_POST['submit'])){
                $Address = sanitize_text_field($_POST['address']);
                $AddressGps = urlencode($Address);
                echo '<script>
                        window.location.href = "' . esc_url(home_url('/polygon-page/')) . '?address=' . esc_js($AddressGps) . '";
                      </script>';
            }
            ?>
        </div>
    </div>
    <?php 
    // Inclusion du fichier footer.php de votre thème WordPress
    get_footer(); 
    ?>
</body>

</html>