const provinces = ["Ninh Bình","Bắc Giang","Hải Phòng","Phú Thọ","Hưng Yên","Bắc Ninh"];
const wards = ["Phường A","Phường B","Phường C","Phường D"];
const streets = ["Đường 1","Đường 2","Đường 3","Đường 4"];
function loadSelect(id,data){
    let select = document.getElementById(id);

    for(let i=0;i<data.length;i++){
        let option = document.createElement("option");
        option.value = data[i];
        option.innerText = data[i];
        select.appendChild(option);
    }
}

loadSelect("province",provinces);
loadSelect("ward",wards);
loadSelect("street",streets);

document.getElementById("name").oninput = function(){

    let value = this.value;
    let regex = /^[A-Za-zÀ-ỹ\s]+$/;
    if(value === "" || regex.test(value)){
        document.getElementById("nameError").innerText = "";
    }else{
        document.getElementById("nameError").innerText = "Chỉ được nhập chữ";
    }
};

document.getElementById("dob").onchange = function(){

    let birth = new Date(this.value);
    let today = new Date();
    let age = today.getFullYear() - birth.getFullYear();
    document.getElementById("age").value = age;
};

document.getElementById("cccd").oninput = function(){
    let value = this.value;
    if(value.length > 12){
        value = value.slice(0,12);
        this.value = value;
    }

    let regex = /^\d{12}$/;

    if(regex.test(value)){
        document.getElementById("cccdError").innerText = "";
    }else{
        document.getElementById("cccdError").innerText = "CCCD phải đủ 12 số";
    }
};

document.getElementById("house").oninput = function(){

    let value = this.value;
    let regex = /^\d+$/;

    if(value === "" || regex.test(value)){
        document.getElementById("houseError").innerText = "";
    }else{
        document.getElementById("houseError").innerText = "Chỉ nhập số";
    }

    updateAddress();
};

function updateAddress(){

    let house = document.getElementById("house").value;
    let street = document.getElementById("street").value;
    let ward = document.getElementById("ward").value;
    let province = document.getElementById("province").value;

    document.getElementById("address").value =
        house + ", " + street + ", " + ward + ", " + province;
}

document.getElementById("street").onchange = updateAddress;
document.getElementById("ward").onchange = updateAddress;
document.getElementById("province").onchange = updateAddress;