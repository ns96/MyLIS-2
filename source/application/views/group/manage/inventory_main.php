<?php

echo $menuHTML;

// --------- CHEMICALS SECTION ----------
echo '<fieldset style="font-weight: bold; color: #3366FF;">';
echo '<legend>Chemicals</legend>';

// add form for importing a tab delimited database file
echo $chemicalsImportForm;
// add form for adding categories
echo $addChemicalCategories;
// add table for editing categories
 echo $editChemicalCategories;

echo '</fieldset>';

// --------- SYPPLIES SECTION -----------
echo '<a name="Supply"></a>'; // used to find it
echo '<fieldset style="font-weight: bold; color: #3366FF;">';
echo '<legend>Supplies</legend>';

// add form for importing a tab delimited database file
echo $suppliesImportForm;
// add form for adding categories
echo $addSupplyCategories;
// add table for editing categories
echo $editSupplyCategories;

echo '</fieldset>';
