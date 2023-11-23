<?php
class Adresse
{
    public $numero;
    public $rue;
    public $ville;
    public $codePostal;
    public $lien; // Add this property
    public $adressePhysiqueVendeur; // Add this property
    
    // For physical addresses
    public function __construct($numero, $rue, $ville, $codePostal)
    {
        $this->numero = $numero;
        $this->rue = $rue;
        $this->ville = $ville;
        $this->codePostal = $codePostal;
    }

    // For internet addresses
    public static function createInternetAdresse($lien, $adressePhysiqueVendeur)
    {
        $adresse = new Adresse(null, null, null, null);
        $adresse->lien = $lien;
        $adresse->adressePhysiqueVendeur = $adressePhysiqueVendeur;
        return $adresse;
    }
}


// class Achat
// {
//     public $articles;
//     public $adresseFacturation;
//     public $montantTotal;
//     public $date;

//     public function __construct($articles, $adresseFacturation, $montantTotal, $date)
//     {
//         $this->articles = $articles;
//         $this->adresseFacturation = $adresseFacturation;
//         $this->montantTotal = $montantTotal;
//         $this->date = $date;
//     }
// }

class Achat
{
    public $articles;
    public $adresseFacturation;
    public $montantTotal;
    public $date;

    public function __construct($articles, $adresseFacturation, $date)
    {
        $this->articles = $articles;
        $this->adresseFacturation = $adresseFacturation;
        $this->montantTotal = $this->calculerMontantTotal();
        $this->date = $date;
    }

    // Calculate the total amount for the entire purchase
    private function calculerMontantTotal()
    {
        $total = 0;
        foreach ($this->articles as $article) {
            $total += $article->produit->montantUnitaire * $article->nombre;
        }
        return $total;
    }
}

class Article
{
    public $produit;
    public $nombre;
    public $adresseLivraison;
    public $type;

    public function __construct($produit, $nombre, $adresseLivraison, $type)
    {
        $this->produit = $produit;
        $this->nombre = $nombre;
        $this->adresseLivraison = $adresseLivraison;
        $this->type = $type;
    }
}

class Produit
{
    public $montantUnitaire;
    public $devise;
    public $nom;
    public $description;
    public $livraison;

    public function __construct($montantUnitaire, $devise, $nom, $description, $livraison)
    {
        $this->montantUnitaire = $montantUnitaire;
        $this->devise = $devise;
        $this->nom = $nom;
        $this->description = $description;
        $this->livraison = $livraison;
    }
}

// Example usage:
$produit1 = new Produit(19.99, '€', 'Nom du Produit 1', 'Description du Produit 1', 'à domicile');
$article1 = new Article($produit1, 2, Adresse::createInternetAdresse('lien_internet_1', 'Adresse Physique Vendeur 1'), 'internet');

$produit2 = new Produit(24.99, '€', 'Nom du Produit 2', 'Description du Produit 2', 'sur place');
$article2 = new Article($produit2, 3, new Adresse(123, 'Rue de l\'exemple', 'Ville Exemple', '12345'), 'physique');

$articles = [$article1, $article2];

$achat = new Achat($articles, new Adresse(456, 'Rue Principale', 'Ville Principale', '67890'), 94.96, '2023-11-20');


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande de Date</title>
    <!-- Ajoutez les liens vers Bootstrap CSS et Font Awesome CSS -->
    <link rel="stylesheet" href="css/bootstrap.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>

<body>
    <div class="container mt-5">
    <?php if ($achat->adresseFacturation instanceof Adresse) : ?>
            <h1 class=" text-center mt-5 mb-5"><?= $achat->adresseFacturation->numero . ' ' . $achat->adresseFacturation->rue . ', ' . $achat->adresseFacturation->ville . ', ' . $achat->adresseFacturation->codePostal; ?> </h1>
            
        <?php endif; ?>
        <h2 class="text-center mb-5"> Commande du <?= $achat->date ?> </h2>

        <h3 class="mt-4">Facture</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Sous-total</th>
                    <th>Lieu d'Achat Icon</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($achat->articles as $article) : ?>
                    <tr>
                        <td><?php echo $article->produit->nom; ?></td>
                        <td><?php echo $article->nombre; ?></td>
                        <td><?php echo $article->produit->montantUnitaire . ' ' . $article->produit->devise; ?></td>
                        <td><?php echo $article->nombre * $article->produit->montantUnitaire; ?> <?php echo $article->produit->devise; ?></td>
                        <td><?php echo $article->type === 'internet' ? '<i class="fas fa-link"></i>' : '<i class="fas fa-store-alt"></i>'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3 class="text-right">
            <strong>Total : <?php echo $achat->montantTotal; ?> <?php echo $article->produit->devise; ?></strong>
        </h3>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>