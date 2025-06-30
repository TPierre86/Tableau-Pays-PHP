<?php
try {
    $dbh = new PDO('mysql:host=localhost;dbname=pays', "utilisateurPays", "azerty123456");
} catch (PDOException $e) {
print $e->getMessage();
print "Connexion échouée.";
}

//------------Déclaraction variables------------------//

$idContinent = isset($_GET['selectContinent'])?($_GET['selectContinent']) : "";
$idRegion = isset($_GET['selectRegion'])?($_GET['selectRegion']) : "";

//------------Importer BdD------------------//

// Aller chercher dans la base de données les pays
$stmt_pays = $dbh->prepare("SELECT * FROM t_pays");
$stmt_pays->execute();

// Aller chercher dans la base de données les régions
if ($idContinent!=="" && $idContinent != 3) {
    $stmt_regions = $dbh->prepare(query: "SELECT * FROM t_regions WHERE continent_id=$idContinent");
    $stmt_regions->execute();
} else {
    $stmt_regions = [];
}

// Aller chercher dans la base de données les continents
$stmt_continents = $dbh->prepare("SELECT * FROM t_continents");
$stmt_continents->execute();

//------------If Else------------------//

if ($idContinent == ""){
    $result = $dbh->prepare("SELECT libelle_continent AS libelle_pays, 
    SUM(population_pays) AS population_pays, 
    AVG(taux_natalite_pays) AS taux_natalite_pays, 
    AVG(taux_mortalite_pays) AS taux_mortalite_pays, 
    AVG(esperance_vie_pays) AS esperance_vie_pays, 
    AVG(taux_mortalite_infantile_pays) AS taux_mortalite_infantile_pays, 
    AVG(nombre_enfants_par_femme_pays) AS nombre_enfants_par_femme_pays, 
    AVG(taux_croissance_pays) AS taux_croissance_pays, 
    AVG(population_plus_65_pays) AS population_plus_65_pays,
    1 AS ordre_affichage
    FROM t_pays 
    INNER JOIN t_continents 
    ON (t_pays.continent_id = t_continents.id_continent)
    GROUP BY libelle_continent

    UNION

    SELECT 'Monde' AS libelle_pays, 
    SUM(population_pays) AS population_pays, 
    AVG(taux_natalite_pays) AS taux_natalite_pays, 
    AVG(taux_mortalite_pays) AS taux_mortalite_pays, 
    AVG(esperance_vie_pays) AS esperance_vie_pays, 
    AVG(taux_mortalite_infantile_pays) AS taux_mortalite_infantile_pays, 
    AVG(nombre_enfants_par_femme_pays) AS nombre_enfants_par_femme_pays, 
    AVG(taux_croissance_pays) AS taux_croissance_pays, 
    AVG(population_plus_65_pays) AS population_plus_65_pays,
    2 AS ordre_affichage
    FROM t_pays 
    INNER JOIN t_continents 
    ON (t_pays.continent_id = t_continents.id_continent)
    ORDER BY ordre_affichage, libelle_pays;");
    
    $result->execute();

}else if ($idContinent == 3){
    $result = $dbh->prepare("SELECT * FROM (
    SELECT 
        libelle_pays, 
        population_pays, 
        taux_natalite_pays, 
        taux_mortalite_pays, 
        esperance_vie_pays, 
        taux_mortalite_infantile_pays, 
        nombre_enfants_par_femme_pays, 
        taux_croissance_pays, 
        population_plus_65_pays,
        1 AS ordre_affichage
    FROM t_pays 
    INNER JOIN t_continents 
        ON t_pays.continent_id = t_continents.id_continent
    WHERE continent_id = 3

    UNION

    SELECT 
        'Total' AS libelle_pays, 
        SUM(population_pays) AS population_pays, 
        AVG(taux_natalite_pays) AS taux_natalite_pays, 
        AVG(taux_mortalite_pays) AS taux_mortalite_pays, 
        AVG(esperance_vie_pays) AS esperance_vie_pays, 
        AVG(taux_mortalite_infantile_pays) AS taux_mortalite_infantile_pays, 
        AVG(nombre_enfants_par_femme_pays) AS nombre_enfants_par_femme_pays, 
        AVG(taux_croissance_pays) AS taux_croissance_pays, 
        AVG(population_plus_65_pays) AS population_plus_65_pays,
        2 AS ordre_affichage
    FROM t_pays 
    INNER JOIN t_continents 
        ON t_pays.continent_id = t_continents.id_continent
    WHERE continent_id = 3
) AS donnees
ORDER BY ordre_affichage, libelle_pays; ");
    $result->execute();
}else {
    $result = $dbh->prepare("SELECT * FROM (
    SELECT libelle_region AS libelle_pays, 
        SUM(population_pays) AS population_pays, 
        AVG(taux_natalite_pays) AS taux_natalite_pays, 
        AVG(taux_mortalite_pays) AS taux_mortalite_pays, 
        AVG(esperance_vie_pays) AS esperance_vie_pays, 
        AVG(taux_mortalite_infantile_pays) AS taux_mortalite_infantile_pays, 
        AVG(nombre_enfants_par_femme_pays) AS nombre_enfants_par_femme_pays, 
        AVG(taux_croissance_pays) AS taux_croissance_pays, 
        AVG(population_plus_65_pays) AS population_plus_65_pays,
        1 AS ordre_affichage
    FROM t_pays 
    INNER JOIN t_regions 
        ON t_pays.region_id = t_regions.id_region
    WHERE t_regions.continent_id = ".$_GET["selectContinent"]."
    GROUP BY libelle_region

    UNION

    SELECT 'Total' AS libelle_pays, 
        SUM(population_pays) AS population_pays, 
        AVG(taux_natalite_pays) AS taux_natalite_pays, 
        AVG(taux_mortalite_pays) AS taux_mortalite_pays, 
        AVG(esperance_vie_pays) AS esperance_vie_pays, 
        AVG(taux_mortalite_infantile_pays) AS taux_mortalite_infantile_pays, 
        AVG(nombre_enfants_par_femme_pays) AS nombre_enfants_par_femme_pays, 
        AVG(taux_croissance_pays) AS taux_croissance_pays, 
        AVG(population_plus_65_pays) AS population_plus_65_pays,
        2 AS ordre_affichage
    FROM t_pays 
    INNER JOIN t_regions 
        ON t_pays.region_id = t_regions.id_region
    WHERE t_regions.continent_id = ".$_GET["selectContinent"]."
) AS resultats
ORDER BY ordre_affichage, libelle_pays;");
    $result->execute();
    if ($idRegion == ""){
        $result = $dbh->prepare("SELECT * FROM (
    SELECT libelle_region AS libelle_pays, 
        SUM(population_pays) AS population_pays, 
        AVG(taux_natalite_pays) AS taux_natalite_pays, 
        AVG(taux_mortalite_pays) AS taux_mortalite_pays, 
        AVG(esperance_vie_pays) AS esperance_vie_pays, 
        AVG(taux_mortalite_infantile_pays) AS taux_mortalite_infantile_pays, 
        AVG(nombre_enfants_par_femme_pays) AS nombre_enfants_par_femme_pays, 
        AVG(taux_croissance_pays) AS taux_croissance_pays, 
        AVG(population_plus_65_pays) AS population_plus_65_pays,
        1 AS ordre_affichage
    FROM t_pays 
    INNER JOIN t_regions 
        ON t_pays.region_id = t_regions.id_region
    WHERE t_regions.continent_id = ".$_GET["selectContinent"]."
    GROUP BY libelle_region

    UNION

    SELECT 'Total' AS libelle_pays, 
        SUM(population_pays) AS population_pays, 
        AVG(taux_natalite_pays) AS taux_natalite_pays, 
        AVG(taux_mortalite_pays) AS taux_mortalite_pays, 
        AVG(esperance_vie_pays) AS esperance_vie_pays, 
        AVG(taux_mortalite_infantile_pays) AS taux_mortalite_infantile_pays, 
        AVG(nombre_enfants_par_femme_pays) AS nombre_enfants_par_femme_pays, 
        AVG(taux_croissance_pays) AS taux_croissance_pays, 
        AVG(population_plus_65_pays) AS population_plus_65_pays,
        2 AS ordre_affichage
    FROM t_pays 
    INNER JOIN t_regions 
        ON t_pays.region_id = t_regions.id_region
    WHERE t_regions.continent_id = ".$_GET["selectContinent"]."
) AS resultats
ORDER BY ordre_affichage, libelle_pays;");
    $result->execute();
} else {
    $result = $dbh->prepare("SELECT * FROM (
    SELECT 
        libelle_pays, 
        population_pays, 
        taux_natalite_pays, 
        taux_mortalite_pays, 
        esperance_vie_pays, 
        taux_mortalite_infantile_pays, 
        nombre_enfants_par_femme_pays, 
        taux_croissance_pays, 
        population_plus_65_pays,
        1 AS ordre_affichage
    FROM t_pays
    WHERE region_id = ".$_GET["selectRegion"]."

    UNION

    SELECT 
        'Total' AS libelle_pays, 
        SUM(population_pays), 
        AVG(taux_natalite_pays), 
        AVG(taux_mortalite_pays), 
        AVG(esperance_vie_pays), 
        AVG(taux_mortalite_infantile_pays), 
        AVG(nombre_enfants_par_femme_pays), 
        AVG(taux_croissance_pays), 
        AVG(population_plus_65_pays),
        2 AS ordre_affichage
    FROM t_pays
    WHERE region_id = ".$_GET["selectRegion"]."
) AS resultats
ORDER BY ordre_affichage, libelle_pays;");
    $result->execute();
}
}
?>

<!-- ------------HTML------------------ -->

<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Document</title>
	</head>
    <body>
        <form id="filterForm">
            <select name="selectContinent" onchange="document.getElementById('filterForm').submit()">
                <option value="">Monde</option>
                <?php foreach($stmt_continents as $continent) { ?>
                    <option value="<?php echo $continent["id_continent"]; ?>" <?php if ($idContinent == $continent['id_continent']) echo 'selected'; ?>>
                        <?php echo $continent["libelle_continent"]; ?>
                    </option>
                <?php } ?>
            </select>

            <select name="selectRegion" onchange="document.getElementById('filterForm').submit()" <?php if($idContinent == "" || $idContinent == 3)  print 'hidden' ?>>
                <option value="">--</option>
                <?php foreach($stmt_regions as $region) { ?>
                    <option value="<?php echo $region["id_region"]; ?>"<?php if ($idRegion == $region['id_region']) echo 'selected'; ?>>
                        <?php echo $region["libelle_region"]; ?>
                    </option>
                <?php } ?>
            </select>
        </form>

        <h1>Liste des pays </h1>

		<table>
			<thead>
				<tr>
					<th>Nom du pays</th>
					<th>Population</th>
					<th>Taux de natalité</th>
					<th>Taux de mortalité</th>
					<th>Espérance de vie</th>
					<th>Taux de mortalité infantile</th>
					<th>Nombre d’enfant(s) par femme</th>
					<th>Taux de croissance</th>
					<th>Part des 65 ans et plus(%)</th>
				</tr>
			</thead>

			<tbody>
				<!-- foreach pour récupérer les infos / possible de faire un fetch pour récupérer info d'une requete SQL-->
				<?php foreach ($result as $row) { ?>
					<tr>
						<td><?php print $row["libelle_pays"] ;?></td>
						<td><?php print $row["population_pays"];?></td>
						<td><?php print $row["taux_natalite_pays"];?></td>
						<td><?php print $row["taux_mortalite_pays"];?></td>
						<td><?php print $row["esperance_vie_pays"];?></td>
						<td><?php print $row["taux_mortalite_infantile_pays"];?></td>
						<td><?php print $row["nombre_enfants_par_femme_pays"];?></td>
						<td><?php print $row["taux_croissance_pays"];?></td>
						<td><?php print $row["population_plus_65_pays"];?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</body>
</html>
<?php $dbh=null; ?>