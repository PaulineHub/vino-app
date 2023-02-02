<?php
session_start();

/**
 * Class Controler
 * Gère les requêtes HTTP
 * 
 * @author Alana Fulvia Bezerra De Moraes, Alex Poulin Truchon, Claudia Lisboa, Pauline Huby
 * @version 2.0
 * @update 2023-02-05
 * 
 */

class Controler
{
	/**
	 * Traite la requête
	 * @return void
	 */
	public function gerer()
	{
		// ID utilisateur et ID de cellier en attendant de recevoir les vraies informations dynamiquement
		$userId = 2;

		switch ($_GET['requete']) {
			case 'listeBouteille':
				$this->listeBouteille($userId, $_SESSION["cellierId"]);
				break;
			case 'informationBouteilleParId':
				$this->informationBouteilleParId();
				break;
			case 'autocompleteBouteille':
				$this->autocompleteBouteille();
				break;
			case 'ajouterNouvelleBouteilleCellier':
				$this->ajouterNouvelleBouteilleCellier();
				break;
			case 'modifierBouteilleCellier':
				$this->modifierBouteilleCellier($userId, $_SESSION["cellierId"], $_GET['bte']);
				break;
			case 'ajouterBouteilleCellier':
				$this->ajouterBouteilleCellier();
				break;
			case 'boireBouteilleCellier':
				$this->boireBouteilleCellier();
				break;
			case 'effacerBouteilleCellier':
				$this->effacerBouteilleCellier($_GET['bteCellier']);
				break;
			case 'inscription':
				$this->inscription();
				break;
			case 'connexion':
				$this->connexion();
				break;
			case 'mesCelliers':
				$this->mesCelliers($userId, $_SESSION["cellierId"]);
				break;
			case 'cellier':
				$this->cellier($userId);
				break;
			case 'ficheDetailsBouteille':
				$this->ficheDetailsBouteille($userId, $_SESSION["cellierId"], $_GET['bte']);
				break;
			default:
				// $this->accueil();
				// $this->cellier($userId, $_SESSION["cellierId"]);
				$this->mesCelliers($userId);
				break;
		}
	}

	/**
	 * Affiche la vue de la page accueil
	 */
	private function accueil()
	{
		include("vues/entete.php");
		include("vues/accueil.php");
	}

	/**
	 * Affiche la vue de la page cellier
	 */
	private function cellier($userId)
	{
		$_SESSION["cellierId"] = $_GET["cellierId"];
		$bte = new Bouteille();
		$data = $bte->getListeBouteilleCellier($userId, $_SESSION["cellierId"]);
		include("vues/entete.php");
		include("vues/navigation.php");
		include("vues/cellier.php");
		include("vues/pied.php");
	}

	/**
	 * Affiche la vue de la page mesCelliers
	 */
	private function mesCelliers($userId)
	{
		$cellier = new Cellier();
		$mesCelliers = $cellier->getCelliers($userId);

		json_encode($mesCelliers);
		include("vues/entete.php");
		include("vues/navigation.php");
		include("vues/mesCelliers.php");
		include("vues/pied.php");
	}

	/**
	 * Affiche la vue de la page Fiche d'un vin
	 */
	private function ficheDetailsBouteille($userId, $cellierId, $idBouteille, $showMessage=false)
	{
		$bte = new Bouteille();
		$dataFiche = $bte->getListeBouteilleCellier($userId, $cellierId, $idBouteille);
		// Afficher message confirmation si modifications
		if ($showMessage) {
				$_SESSION["message"] = "Modifications enregistrées !";
				$_SESSION["estVisible"] = true;
		} 
		include("vues/entete.php");
		include("vues/navigation.php");
		include("vues/fiche.php");
		include("vues/pied.php");
	}

	/**
	 * 
	 */
	private function listeBouteille($userId, $cellierId)
	{
		$bte = new Bouteille();
		$cellier = $bte->getListeBouteilleCellier($userId, $cellierId);

		echo json_encode($cellier);
	}

	/**
	 * 
	 */
	private function autocompleteBouteille()
	{
		$bte = new Bouteille();
		//var_dump(file_get_contents('php://input'));
		$body = json_decode(file_get_contents('php://input'));
		//var_dump($body);
		$listeBouteille = $bte->autocomplete($body->nom);

		echo json_encode($listeBouteille);
	}

	/**
	 * Modifie les informations d'un vin dans un cellier
	 */
	private function modifierBouteilleCellier($userId, $cellierId, $idBouteille)
	{
		$type = new Type();
		$types = $type->getTypes();

		$body = $_POST;

		if (!empty($body)) {
			$bte = new Bouteille();
			$modifier = $bte->modifierBouteilleCellier($body);

			// Message pop-up confirmation modification faite
			$showMessage = false;
			if ($modifier) $showMessage = true;

			$this->ficheDetailsBouteille($userId, $cellierId, $idBouteille, $showMessage);
		} else {
			$dataTypesModifier = $types;

			$bte = new Bouteille();
			$dataModifie = $bte->getListeBouteilleCellier($userId, $cellierId, $idBouteille);

			include("vues/entete.php");
			include("vues/navigation.php");
			include("vues/modifier.php");
			include("vues/pied.php");
		}
	}

	/**
	 * Supprime un vin d'un cellier
	 */
	private function effacerBouteilleCellier($idBouteilleCellier)
	{
		$bte = new Bouteille();
		$effacer = $bte->effacerBouteilleCellier($idBouteilleCellier);
		// Message pop-up confirmation bouteille supprimée
		$_SESSION["message"] = "Bouteille supprimée !";
		$_SESSION["estVisible"] = true;
		// Redirection page cellier
		Utilitaires::nouvelleRoute('index.php?requete=cellier&cellierId=' . $_SESSION["cellierId"] . '');
	}

	/**
	 * 
	 */
	private function informationBouteilleParId()
	{
		$bte = new Bouteille();
		$id = $_GET["id"];
		$bouteille = $bte->getBouteilleParId($id);
		echo json_encode($bouteille);
	}

	/**
	 * Ajoute un nouveau vin dans un cellier
	 */
	private function ajouterNouvelleBouteilleCellier()
	{
		$type = new Type();
		$types = $type->getTypes();

		$body = json_decode(file_get_contents('php://input'), true);
		if (!empty($body)) {
			$bte = new Bouteille();
			$resultat = $bte->ajouterBouteilleCellier($body);
			if($resultat === false){
				$_SESSION["message"] = "Bouteille déjà créée.";
				$_SESSION["estVisible"] = true;
			} else {
				$_SESSION["message"] = "Bouteille ajoutée !";
				$_SESSION["estVisible"] = true;
			}
			die();
		} else {
			$dataTypes = $types;

			include("vues/entete.php");
			include("vues/navigation.php");
			include("vues/ajouter.php");
			include("vues/pied.php");
		}
	}

	/**
	 * Diminue la quantite de bouteilles d'un vin dans un cellier
	 */
	private function boireBouteilleCellier()
	{
		$body = json_decode(file_get_contents('php://input'));

		$bte = new Bouteille();
		$resultat = $bte->modifierQuantiteBouteilleCellier($body->id, -1);
		echo json_encode($resultat);
	}

	/**
	 * Augmente la quantite de bouteilles d'un vin dans un cellier
	 */
	private function ajouterBouteilleCellier()
	{
		$body = json_decode(file_get_contents('php://input'));

		$bte = new Bouteille();
		$resultat = $bte->modifierQuantiteBouteilleCellier($body->id, 1);
		// var_dump($resultat);
		echo json_encode($resultat);
	}

	/**
	 * Affiche la vue de la page inscription
	 */
	private function inscription()
	{
		include("vues/entete.php");
		include("vues/inscription.php");
		include("vues/pied.php");
	}

	/**
	 * Affiche la vue de la page connexion
	 */
	private function connexion()
	{
		include("vues/entete.php");
		include("vues/connexion.php");
		include("vues/pied.php");
	}
}
