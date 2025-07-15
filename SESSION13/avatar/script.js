$("#inputImg").change(function(e){

img = URL.createObjectURL(e.target.files[0])
$("#imgPreview").attr("src", img)

});