window.onload = function(){
    //localStorage.removeItem('list')
    populate_cart();


}

console.log('yes')

function add_to_cart(p_id, e) {
    e.stopPropagation();
    if(localStorage.getItem('list')==null){
        let list = []
        localStorage.setItem('list', JSON.stringify(list))
    }
    let quan = 0
    if(document.querySelector('#propage_quan')!=null){
        let pro_quan= document.querySelector('#propage_quan')
        quan = parseInt(pro_quan.value)
    }
    else{
        quan = 1
    }

    let list = localStorage.getItem('list')
    list = JSON.parse(list)
    let obj = {pid: p_id, quantity: quan}
    let isExist = false
    for(let i = 0; i < list.length; i++){
        if(list[i].pid == p_id){
            isExist = true
        }
    }
    if(!isExist){
        list.push(obj)
    }
    else {
        for(let i = 0; i < list.length; i++){
            if(list[i].pid == p_id){
                list[i].quantity+=quan
            }
        }
    }

    localStorage.setItem('list', JSON.stringify(list))
    populate_cart()

}


function populate_cart(){
    let list = localStorage.getItem('list')
    if(list!=null){
        let str = "";
        list = JSON.parse(list)
        if(list.length==0){
            removeAllItems();
            return
        }
        let total = 0
        for(let i = 0; i < list.length; i++){
            let temp = Ajax(list[i].pid)
            str+="<div class='cart_view_item'><span>"+temp[0].name+"</span> \
                  <input id='q_"+list[i].pid+"' name=\"quantity\" value='"+ list[i].quantity +"' disabled='disabled'/> \
                  <button onclick='quan_plus("+list[i].pid+", "+temp[0].price+")'>+</button> \
                  <button onclick='quan_minus("+list[i].pid+", "+temp[0].price+")'>-</button> \
                  <span id='p_"+list[i].pid+"'>HK$ "+temp[0].price * list[i].quantity+"</span>\
                  <button onclick='removeItem("+i+")'>remove</button> \
                  </br><hr></div>"
            total+=temp[0].price * list[i].quantity

        }
        let cart = document.querySelector('#cart_view')
        cart.innerHTML = str+"<h1>Total: HK$ "+total+"</h1><div><button name=\"sumit\" id='checkbutton'>Check Out</button> \
                         <button id='removeallbutton' onclick='removeAllItems()'>Remove All</button></div>"
    }


}

function Ajax(p_id) {
    let rel
    $.ajax({
        url: './shoppingCart.php',
        async:false,
        type: 'POST',
        data: {
            pid: p_id
        },
        success: (result)=>{
            //return result;
            rel = result

        }
    })
    return JSON.parse(rel);
}

function quan_plus(pid, price){
    let list = localStorage.getItem('list')
    list = JSON.parse(list)
    for(let i = 0; i < list.length; i++){
        if(list[i].pid == pid){
            list[i].quantity++
        }
    }
    localStorage.setItem('list', JSON.stringify(list))
    populate_cart()


}

function quan_minus(pid, price){
    let list = localStorage.getItem('list')
    list = JSON.parse(list)
    for(let i = 0; i < list.length; i++){
        if(list[i].pid == pid){
            if(list[i].quantity==1){
                return
            }
            list[i].quantity--
        }
    }
    localStorage.setItem('list', JSON.stringify(list))
    populate_cart()


}


function removeItem(index) {
    let list = localStorage.getItem('list')
    list = JSON.parse(list)
    list.splice(index, 1)
    localStorage.setItem('list', JSON.stringify(list))
    populate_cart()
}

function removeAllItems() {
    localStorage.removeItem('list')
    let cart = document.querySelector('#cart_view')
    cart.innerHTML = "<div>Cart is empty</div>"
}