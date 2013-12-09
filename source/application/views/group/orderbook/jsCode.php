<?php

$ajaxlink1 = base_url()."group/orderbook/itemlist/getList"; // uses ajax to load the list of saved items for this company
$itemlist_link = base_url()."group/orderbook/itemlist/companyItems"; // function to display a page wih items from a particular company 
$remove_company_link = base_url()."group/orderbook/orders/removeCompany?order_id=$order_id&company="; // reomve a saved company
$remove_account_link = base_url()."group/orderbook/orders/remove_account?order_id=$order_id&account="; // reomve a saved company
$add_tally_link = base_url()."group/orderbook/orders/addTally?order_id=$order_id&account=";  // add current account to the tally list
$remove_tally_link = base_url()."group/orderbook/orders/removeTally?order_id=$order_id&account=";  // remove the current account from tally list

// some javascript code
echo '<script language="Javascript">
<!--Hide script from older browsers
var http = createRequestObject(); // used for ajax

var companies = new Array('.$js_cnames.');

var defaultAccounts = new Array();';
echo $defaultAccounts;

echo 'var selectedCompany = ""; // stores the cuurently selected company

var itemLists; // stores a list of items for the currently selected company

var maxItems = '.$maxitems.';

function changeCompany(companies, num) {
    var name = companies.options[companies.selectedIndex].value;
    var account = defaultAccounts[name];
    if(num == 0) {
    document.forms.form1.company_0.value = name;
    if(typeof(account) != "undefined") {
	document.forms.form1.account.value = account;
    }

    // load save items for this company
    sndItemListReq(name);
    selectedCompany = name;
    }

    //alert("This works " + name + " Default Account " + account);
}

function putCompany(company) {
    var num = company.value;

    if(num >= 1 &&  num <=  companies.length) {
    company.value = companies[num -1];
    }
}

function changeAccount(accounts, num) {
    var name = accounts.options[accounts.selectedIndex].value;
    if(num == 0) {
    document.forms.form1.account.value = name;
    }
}

// function to search for add an item order list if it is found
function putProduct(product, type, company, description, units, price) {
    var item = itemList[product.value];
    var itemInfo = new Array();

    if(typeof(item) != "undefined") {
    itemInfo = item.split(\'\t\');
    if(itemInfo[1] == "Chemical") {
	type.selectedIndex = 0;
    }
    else if(itemInfo[1] == "Supply") {
	type.selectedIndex = 1;
    }
    else {
	type.selectedIndex = 2;
    }
    company.value = itemInfo[2];
    product.value = itemInfo[3];
    description.value = itemInfo[4];
    units.value = itemInfo[5];
    price.value = "$"+itemInfo[6];
    }
}

function submitOrder(task) {
    // check to see if company is missing
    var company = document.forms.form1.company_0.value;
    if(company.length == 0) {
    alert("Please Select A Company");
    return;
    }

    //check to see if account is missing
    var account = document.forms.form1.account.value;
    if(account.length == 0) {
    alert("Please Select An Account");
    return;
    }

    document.forms.form1.task2.value = task;
    document.forms.form1.submit();
}

// some stuff for AJAX
function createRequestObject() {
    var ro;
    var browser = navigator.appName;
    if(browser == "Microsoft Internet Explorer"){
    ro = new ActiveXObject("Microsoft.XMLHTTP");
    } else{
    ro = new XMLHttpRequest();
    }
    return ro;
}

function sndItemListReq(company) {
    http.open(\'get\', \''.$ajaxlink1.'&company=\'+company);
    http.onreadystatechange = handleResponse;
    http.send(null);
}

// this stores the list of save items
function handleResponse() {
    itemList = new Array(); // clear the item list array

    if(http.readyState == 4){
    var response = http.responseText;
    var update = new Array();
    var itemInfo = new Array();
    var x;
    var test = "";

    if(response.indexOf(\'||\' != -1)) {
	update = response.split(\'||\');
	for(x in update) {
	itemInfo = update[x].split(\'\t\');
	itemList[itemInfo[0]] = update[x];
	}
    }
    }
} //--end of ajax stuff

// function to open the item lists page for the selected company
function openItemListPage() {
    if(selectedCompany != null && selectedCompany.length != 0) {
    var title = \'itempage\';
    var link = \''.$itemlist_link.'&company=\' + selectedCompany; 
    window.open(link,title,\'width=500,height=600,location=no,resizable=yes, scrollbars=yes\'); 
    } else {
    alert("Please Select A Company");
    }
}

// function to add a produc to an open slot in the item list
function putProduct2(itemNum) {
    var item = itemList[itemNum];
    var itemInfo = new Array();

    if(typeof(item) != "undefined") {
    itemInfo = item.split(\'\t\');

    // find an open slot now
    var i;
    for(i = 1; i <= maxItems; i++) {
	var type = document.getElementsByName("type_" + i);
	var company = document.getElementsByName("company_" + i);
	var product = document.getElementsByName("product_" + i);
	var description = document.getElementsByName("description_" + i);
	var units = document.getElementsByName("units_" + i);
	var price = document.getElementsByName("price_" + i);

	// check to see this is an empty slot
	if(product[0].value.length == 0 && description[0].value.length == 0) {
	if(itemInfo[1] == "Chemical") {
	    type[0].selectedIndex = 0;
	}
	else if(itemInfo[1] == "Supply") {
	    type[0].selectedIndex = 1;
	}
	else {
	    type[0].selectedIndex = 2;
	}
	company[0].value = itemInfo[2];
	product[0].value = itemInfo[3];
	description[0].value = itemInfo[4];
	units[0].value = itemInfo[5];
	price[0].value = "$"+itemInfo[6];
	break;
	}
    }
    }
}

// function to remove the current company
function removeSelectedCompany() {
    if(selectedCompany.length == 0) {
    alert("Please Select A company");
    }
    else if(confirm("Remove " + selectedCompany + " from the Company List?")) {
    window.location = "'.$remove_company_link.'"+selectedCompany;
    }
}

// function to remove the selected account
function removeSelectedAccount() {
    var account = document.forms.form1.accounts.options[document.forms.form1.accounts.selectedIndex].value;
    if(account.length == 0) {
    alert("Please Select An Account");
    }
    else if(confirm("Remove " + account + " from the Account List?")) {
    window.location = "'.$remove_account_link.'"+account;
    }
}

// function to add an account for tallying
function addAccountToTallyList() {
    var account = document.forms.form1.accounts.options[document.forms.form1.accounts.selectedIndex].value;
    if(account.length == 0) {
    alert("Please Select An Account");
    }
    else if(confirm("Add " + account + " to Tally List?")) {
    window.location = "'.$add_tally_link.'"+account;
    }
}

// function to remove the tally link
function removeAccountFromTallyList() {
    var account = document.forms.form1.accounts.options[document.forms.form1.accounts.selectedIndex].value;
    if(account.length == 0) {
    alert("Please Select An Account");
    }
    else if(confirm("Remove " + account + " from Tally List?")) {
    window.location = "'.$remove_tally_link.'"+account;
    }
}

// End hiding script from older browsers-->              
</script>';