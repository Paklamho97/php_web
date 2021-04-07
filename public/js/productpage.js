let pro_quan = document.querySelector("#propage_quan")

function opr_plus() {
    pro_quan.value++
}
function opr_minus() {
    if(pro_quan.value==1){
        return
    }
    pro_quan.value--
}