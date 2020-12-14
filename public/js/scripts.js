
function addIdTo(valor, id) {
    document.getElementById(id).value=valor;
};

function showModalById(id){
    document.getElementById(id).style.display='block';
}
function hideModalById(id){
    document.getElementById(id).style.display='none'
}
function changeAction(action, formulario){
    document.getElementById(formulario).action=action;
}
function changeViewById(id, display){
    document.getElementById(id).style.display=display;
}
function w3Display(id) {
    var x = document.getElementById(id);
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
    } else {
        x.className = x.className.replace("w3-show", "");
    }
}
function getImoSubclass(imoClass){
    $.ajax({
        type:"POST",
        url:"/Supervisor/getImoSubclass",
        data:"id="+imoClass,
        dataType: "json",
        success:function(result){

            $("label").remove("#imoS");
            $( "#hazardRadio" ).append("<label id='imoS' class='w3-container'>\n" +
                "<span class='w3-block'>IMO Subclass: </span>\n" +
                "<select class='w3-select w3-border' name='imoSubclass' id='imoSubclass'>\n" +
                "</select>\n" +
                "</label>");
            $("#imoSubclass").append("<option value='' disabled selected>Elija la subclase IMO</option>\n");
            $.each(result, function(index, data) {
                $( "#imoSubclass" ).append(new Option('Subclase '+ data.nroSubclase +'. '+ data.especialidadSub,data.idSubclase));
            });
        }
    });
}