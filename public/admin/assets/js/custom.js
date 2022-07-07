$(document).ready(function(){
    $(".datatable").DataTable();



    $(".copie-asset-url").click(function(){
        let url = $(this).attr('url');
        console.log(url);
 
        navigator.clipboard.writeText(url);

        $(this).html("copiée")
    });
 




    $("#add-new-contract-feild-form").on('submit',function(e){
        e.preventDefault(); 
        let val = $(this).serializeArray(); 
        console.log(val); 


        let blocProperty = `
        <li class="list-group-item d-flex justify-content-between align-items-start property-item-contract">
            <input class="name-feild" type="hidden" name="` +val[0].name+`" value="` +val[0].value+`" />
            <input class="id-feild"  type="hidden" name="` +val[1].name+`" value="` +val[1].value+`" />
            
            <div class="ms-2 me-auto">
            <div class="fw-bold ">Nom du champ : ` +val[0].value+`</div>
            ID : ` +val[1].value+`
            </div>
            <span class="badge bg-danger rounded-pill remove-propertie">supprimer</span>
        </li> 
        `; 

        // send data via ajax 
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: {  
                feildName : val[0].value, fieldID : val[1].value, feildType : val[2].value
            },
            success: function (data) {
                console.log('Submission was successful.');
                console.log(data);
                if (data.success === true) {
                    $("#selected-properties-list").append(blocProperty);
                }else{
                    alert(data.message)
                }
            },
            error: function (data) {
                 alert("quelque chose s'est mal passé")
            },
        });


        // delete item
        $(".remove-propertie").click(function(){
             
            let propID = $(this).parent().children('.prop-id').val();
            $.ajax({
                type: 'POST',
                url: '/web-master/contract-properties/delete/'+propID,
                data: {  
                    propID : propID 
                },
                success: function (data) {
                    $(this).parent().remove();
                },
                error: function (data) {
                     alert("quelque chose s'est mal passé")
                },
            });

        })
    })

 
 

    $(".remove-propertie").click(function(){
        let item = $(this);
        let propID = $(this).parent().children('.prop-id').val();
        $.ajax({
            type: 'POST',
            url: '/web-master/contract-properties/delete/'+propID,
            data: {  
                propID : propID 
            },
            success: function (data) {
               if (data.success ===true) {
                item.parent().remove();
               }
            },
            error: function (data) {
                 alert("quelque chose s'est mal passé")
            },
        });

    })

    
});