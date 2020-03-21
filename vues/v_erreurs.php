<?php
/**
 * Vue Erreurs
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Bet Sefer
 * @author    Méora Pia Brami
 */
?>
<div class="alert alert-danger" role="alert">
    <?php
    foreach ($_REQUEST['erreurs'] as $erreur) {
        echo '<p>' . htmlspecialchars($erreur) . '</p>';
    }
    ?>
</div>