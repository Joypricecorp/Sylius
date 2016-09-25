function updateSpinner(obj) {
    var contentObj = document.getElementById("vcare_cart_item_quantity");
    var value = parseInt(contentObj.value);

    if (obj.id == "down") {
        value--;
    } else {
        value++;
    }
    contentObj.value = value;
}
