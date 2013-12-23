<?php
echo $menuHTML;

// --------- CHEMICALS SECTION ----------

echo "<div style='font-size:20px; font-weight:bold'>Chemicals <img src='".base_url()."images/icons/tubes.png' title='back'/></div>";

// add form for importing a tab delimited database file
echo $chemicalsImportForm;
// add form for adding categories
echo $addChemicalCategories;
// add table for editing categories
 echo $editChemicalCategories;


// --------- SYPPLIES SECTION -----------
echo '<a name="Supply"></a>'; // used to find it

echo "<div style='font-size:20px; font-weight:bold'>Supplies <img src='".base_url()."images/icons/supplies.png' title='back'/></div>";

// add form for importing a tab delimited database file
echo $suppliesImportForm;
// add form for adding categories
echo $addSupplyCategories;
// add table for editing categories
echo $editSupplyCategories;

