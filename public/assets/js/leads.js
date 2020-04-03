$("#add-lead").click(function(){
    $("#lead-panel").toggle("slow");
    
});

$("#fechar").click(function(){
    $("#lead-panel").hide("slow");
    
});

$("#lead").click(function(){
    $(".sucesso").show("slow");
    setTimeout(function() { 
        $(".sucesso").hide("slow");
    }, 2000);
    
});