<div class="cellier">
    <div class="menu-actions-container">
        <a class="no-underline" href="?requete=ajouterNouvelleBouteilleCellier">
            <span class="bouton-carre-label">Ajouter une bouteille</span>
            <div class="bouton-carre"><i class="fa-solid fa-plus"></i></div>    
        </a>
    </div>
    <section class="bouteilles-container">  
    <?php
    foreach ($data as $cle => $bouteille) {
        ?>
        <div class="bouteille" data-quantite="<?php echo $bouteille['quantite'] ?>">
            <div class="bouteille-img-container">
                <a href="?requete=afficherDetailsBouteilleCellier">
                <?php if ($bouteille['image']) : ?>
                   <img src="https:<?php echo $bouteille['image'] ?>"> 
                <?php else: ?>
                    <img src="./images/vin-fallback.png">
                <?php endif; ?>
                </a>
            </div>
            <div class="description">
                <a href="?requete=afficherDetailsBouteilleCellier">
                <p class="type <?php echo strtolower($bouteille['type']) ?>"><?php echo $bouteille['type'] ?></p>          
                <p class="nom"><?php echo $bouteille['nom'] ?></p>
                <p class="pays"><?php echo $bouteille['pays'] ?></p>
                <?php if ($bouteille['millesime'] != 0) : ?>
                    <p class="millesime">Millesime <?php echo $bouteille['millesime'] ?></p>
                <?php endif; ?>
                </a>
                <div class="quantity-wrapper">
                    <div class="bouton-carre btnBoire" data-id="<?php echo $bouteille['id_bouteille_cellier'] ?>">
                        <i class="fa-solid fa-minus btnBoire" data-id="<?php echo $bouteille['id_bouteille_cellier'] ?>"></i>
                    </div>
                    <p class="quantite"><?php echo $bouteille['quantite'] ?></p>
                    <div class="bouton-carre btnAjouter" data-id="<?php echo $bouteille['id_bouteille_cellier'] ?>">
                        <i class="fa-solid fa-plus btnAjouter" data-id="<?php echo $bouteille['id_bouteille_cellier'] ?>"></i>
                    </div>
                </div>
            </div>
        </div>
    <?php


    }

    ?>	
    </section> 
</div>


