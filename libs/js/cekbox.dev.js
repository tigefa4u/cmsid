var jumlahnya;
function checkbox_all(){
    jumlahnya = document.getElementById("checkbox_total").value;
    if(document.getElementById("set_checkbox_all").checked==true){
		
        for(i=0;i<jumlahnya;i++){
            document.getElementById("checkbox_id_"+i).checked = true;
        }
    }else{
        for(i=0;i<jumlahnya;i++){
            document.getElementById("checkbox_id_"+i).checked = false;
        }
    }
}

function confirm_checkbox(){
    ada = 0;            //untuk mengecek apakah ada checkbox yang dicek
    semuanya = 1;    //untuk mengecek apakah semua checkbox tercek
    
    //untuk mengambil jumlah total checkbox yang ada
    jumlahnya = document.getElementById("checkbox_total").value;
    
    jumlahx = 0         //untuk mengetahui jumlah yang dicek
    for(i=0;i<jumlahnya;i++){
        idcek = "checkbox_id_"+i;
        if(document.getElementById(idcek).checked == true){
            jumlahx++;
            ada = 1;
        }else{
            semuanya = 0;
        }
    }
    if(ada==1){
        if(semuanya == 1){
            tanya = confirm("Yakin mau di hapus?");
            if(tanya == 1){
                document.getElementById("checkbox_go").submit();
            }
        }else{
            tanya = confirm("Yakin mau di hapus "+jumlahx+" item ?");
            if(tanya == 1){
                document.getElementById("checkbox_go").submit();
            }
        }
    }else{
		alert("Silahkan centang table yg akan di hapus!");
	}
}