$(document).ready(function () {

    function animateFeild(feildID) {

        $("#" + feildID + "").addClass("animate-feild");
        setTimeout(() => {
            $("#" + feildID + "").removeClass("animate-feild");
        }, 1000)
    }


    $(".contract-input").on('keyup', function (e) {
        console.log(e);
        let target = $(this).attr('target');
        animateFeild(target);

        const feildType = $(this).attr('type');
        
        if ( feildType ==='date' ) {
            console.log(feildType);
            let val = $(this).val();
            
            let date = new Date(val);
            let res = (date.getDate().toString().length == 1 ? ('0'+date.getDate().toString()) : date.getDate().toString() )   +'/'+( (date.getMonth()+1).toString().length == 1 ? ('0'+(date.getMonth()+1).toString()) : date.getDate().toString()   )+'/'+date.getFullYear()
            $("#" + target + "").html(res);
        }else{
            let val = $(this).val();
            $("#" + target + "").html(val);
    
        }


    })

    $(".contract-input").on('change', function (e) {
        console.log(e);
        let target = $(this).attr('target');
        animateFeild(target);

        
        const feildType = $(this).attr('type');

        if ( feildType ==='date' ) {
            console.log(feildType);
            let val = $(this).val();
            
            let date = new Date(val);
            let res = (date.getDate().toString().length == 1 ? ('0'+date.getDate().toString()) : date.getDate().toString() )   +'/'+( (date.getMonth()+1).toString().length == 1 ? ('0'+(date.getMonth()+1).toString()) : date.getDate().toString()   )+'/'+date.getFullYear()
            $("#" + target + "").html(res);
        }else{
            let val = $(this).val();
            $("#" + target + "").html(val);
    
        }
    })


    var currentStepIndex = 1;
    var maxSteps = 0;


    $(".previous-properties-step").click(function () {

        console.log(currentStepIndex);
        let currentStep = $(".step-" + currentStepIndex + "")
        let nextStep = $(".step-" + (currentStepIndex - 1) + "")

        console.log(nextStep);

        // 


        if (nextStep && currentStepIndex > 1) {
            currentStep.addClass('hidden');
            nextStep.removeClass('hidden');
            currentStepIndex -= 1;

            let nbrSteps = Number($(".contract-progress").attr("steps"));
            /**** progress **** */
            // init progress %
            let progress = ((100 / nbrSteps) * currentStepIndex).toFixed();
        
            $(".contract-progress").css({width:progress+'%'});
            $(".contract-progress").html(progress+'%');

        }

        // did we reach final step ?
        if (currentStepIndex != maxSteps) {
            $(".print-doc").fadeOut();
            $(".next-properties-step").fadeIn();
            $(".contract-progress").removeClass('bg-success')
        }

    })


    $(".next-properties-step").click(function () {

        // update the max steps
        maxSteps = Number($(this).attr("steps"));

        let currentStep = $(".step-" + currentStepIndex + "")
        let nextStep = $(".step-" + (currentStepIndex + 1) + "")

        if (nextStep && currentStepIndex != maxSteps) {
            currentStep.addClass('hidden');
            nextStep.removeClass('hidden');
            currentStepIndex += 1;

            let nbrSteps = Number($(".contract-progress").attr("steps"));
        /**** progress **** */
        // init progress %
        let progress = ((100 / nbrSteps) * currentStepIndex).toFixed();
    
        $(".contract-progress").css({width:progress+'%'});
        $(".contract-progress").html(progress+'%');

        } 

        // next step

        


        // did we reach final step ?
        if (currentStepIndex == maxSteps) {
            $(".print-doc").fadeIn();
            $(".next-properties-step").fadeOut();

            $(".contract-progress").addClass('bg-success')
        }


        

    })


    $(".print-doc").click(function(){
        let printDocButton = $(this);

        printDocButton.hide();

        let blocToSaveInUserDocumentList = '<div>'+$('#contract-zone').html()+'</div>';
        let docID = $("#contract-id").val();
        // show Loader
        $(".loader-alert").slideDown();
        $(".error-alert").slideUp()
        $.ajax({
            type: 'POST',
            url: '/account/save-document',
            data: {  
                 content : blocToSaveInUserDocumentList,
                 docID : docID
            },
            success: function (data) {
                console.log(data);
               if (data.success ===true) { 
                $(".success-alert").slideDown()
                
                setTimeout(()=>{
                    let w=window.open();
                    w.document.write($('#contract-zone').html());
                    w.print();
                    w.close();

                },3000)
                
               }else{
                   // error
                   $(".error-alert").slideDown()
               }
            },
            error: function (data) {
                 // show error Bloc
                 console.log(data);
                 $(".error-alert").slideDown()

            },
            complete: function(data) { 
                 // hide loader
                 printDocButton.show();
                 $(".loader-alert").slideUp();
            }
        });

        

    })



    let nbrSteps = Number($(".contract-progress").attr("steps"));
    /**** progress **** */
    // init progress %
    let progress = (100 / nbrSteps).toFixed();

    $(".contract-progress").css({width:progress+'%'});
    $(".contract-progress").html(progress+'%');



    $(".update-profile-photo").click(function(){
        $("#photo").click();

    });

    $("#photo").change(function(){
        $("#update-photo-form").submit();
        
    });



    $(".print-doc-from-profile").click(function(){
        let data = $(this).attr('data-to-print');
        let w=window.open();
        w.document.write(data);
        w.print();
        w.close();
    })


    $(".to-contracts").click(function(){
        window.location='/contracts-category/'+$(this).attr('target')
    })

})