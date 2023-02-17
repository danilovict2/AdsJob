<?php 
require __DIR__ . '/navbar.php'; 
?>
<link rel="stylesheet" href="../css/search.css">
<div class="container">
    <form action="">
        <div id="search-container">
            <div class="usluga">
                <div class="usluga-label">
                    <label>Šta?</label>
                </div>
                <div class="usluga-search">
                    <input type="text" class="search" placeholder="Naziv usluge, ključne reči">
                    <span>
                        
                    </span>
                </div>
            </div>
            <div class="usluga">
                <div class="usluga-label">
                    <label>Gde?</label>
                </div>
                    <input type="text" class="search" placeholder="Izaberi grad">
                    <span>
                       
                    </span>
            </div>
        </div>
        <input type="submit" class="pretraga" value="Pronadji">
    </form>
</div>