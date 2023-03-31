<head>
	<link rel="stylesheet" href="../styles/publish.css">
</head>

<?php
function get_categories(){
	$db = Db::getInstance();
	$query = "SELECT name, value FROM categories;";
	$res = $db->query($query);
	$categories = array();
	while($category = $res->fetch_object()){
		array_push($categories, $category);
	}

	return $categories;
}
?>
<div class="ad">
<div class="title">Objavi oglas</div>
<form action="?controller=ads&action=store" method="POST" enctype="multipart/form-data" id="myform">
    <div class="divider">
        <div class="section1">
            <div class="f_main">
                <div class="labels">
                    <label>Naslov</label>
                    <label>Vsebina</label>
                </div>
                <div class="inputs">
                    <input type="text" name="title" required/>
                    <textarea name="description" rows="10" cols="50" required></textarea>
                </div>
            </div>
            <label>Slika</label>
            <button id="upload_img" type="button">Izberi slike</button>
            <input type="file" name="images[]" multiple accept="image/*"/> <br/>
        </div>
        <div class="categories section2">
            <div class="heading">Kategorije:</div>
                <?php
                    $categories = get_categories();
                    foreach($categories as $category){
                        ?>
                        <div class="item">
                            <input class="styled" type="checkbox" name="categories[]" value="<?php echo $category->name ?>" id="<?php echo $category->name ?>">
                            <label for="<?php echo $category->name ?>"> <?php echo $category->value ?> </label>
                        </div>
                        <?php
                    }
                ?>
            </div>
        </div>
    <div class="images"></div>
    <input type="submit" name="submit" value="Objavi" id="submitBtn"/> <br/>
</form>
</div>
<script>
    //upload imgs
    $("#upload_img").click(() => {
        $("input[type='file']").trigger("click")
    })

    var images = []
    var cover

    const fileInput = document.querySelector('input[type="file"]')
    const preview = document.querySelector(".images")

    fileInput.addEventListener('change', () => {

        for(const file of fileInput.files){
            const reader = new FileReader()

            reader.addEventListener('load', () => {
                if(!cover){
                    cover = file
                    images.push(file)
                    add_image(reader.result, "flag")
                }
                else{
                    images.push(file)
                    add_image(reader.result)
                }
                img_tools()
            })

            if(file){
                reader.readAsDataURL(file)
            }
        }
    })

    const form = document.getElementById("myform")

    // submit
    form.addEventListener("submit", (e) => {
        e.preventDefault()

        const sub_btn = $("#submitBtn")
        sub_btn.disabled = true

        const formData = new FormData()

        const formElements = form.elements

        var catFlag = false;
        var covFlag = true;

        for(let i = 0; i < formElements.length; i++){
            var element = formElements[i]

            // dobi vse vrednosti razen image
            if(element.name !== "images[]"){
                if(element.type !== "checkbox"){
                    formData.append(element.name, element.value)
                }
                else if(element.checked){
                    formData.append(element.name, element.value)
                    catFlag = true;
                }
            }
        }

        if(images.length > 0){
            // dobi slike
            for(let i = 0; i < images.length; i++){
                formData.append("images[]", images[i])
            }

            // cover image
            if(cover){
                formData.append("cover", cover.name)
            }
            else{
                covFlag = false;
            }
        }

        if(catFlag && covFlag && images.length > 0){
            $.ajax({
                type: 'POST',
                url: '?controller=ads&action=store',
                data: formData,
                cache: false,
                async: false,
                contentType: false,
                processData: false,
                success: (response) => {
                    $('html').html(response)
                }
            })
        }
        else if(images.length <= 0){
            $("#error").css("display", "block")
            $("#error").text("Oglas mora vsebovati vsaj eno sliko")
        }
        else if(!catFlag){
            $("#error").css("display", "block")
            $("#error").text("Oglas mora imeti vsaj eno kategorijo")
        }
        else if(!covFlag){
            $("#error").css("display", "block")
            $("#error").text("Oglas mora imeti naslovno sliko")
        }
        
    })

    function action(i){
        if(images[i].name == cover.name){
            cover = undefined
        }
        images.splice(i, 1)
        refresh_images()
    }

    function action_c(i){
        $('.image').removeClass('cover_img')
        $('.image').eq(i).addClass('cover_img')
        cover = images[i]
    }

    // brisanje slik
    function img_tools(){
        var del_buttons = document.getElementsByClassName("btn_delete")
        var cover_buttons = document.getElementsByClassName("btn_cover")

        for(let i = 0; i < del_buttons.length; i++){
            const delAction = action.bind(null, i)
            const coverAction = action_c.bind(null, i)
            $(del_buttons[i]).unbind()
            $(cover_buttons[i]).unbind()
            $(del_buttons[i]).bind("click", delAction)
            $(cover_buttons[i]).bind("click", coverAction)
        }
    }

    function add_image(img, flag){
        flag = flag || 0
        var temp = img
        if(typeof img === 'object'){
            if('lastModified' in img){
                const reader = new FileReader()

                    reader.addEventListener('load', () => {
                        temp = reader.result
                        add_image_2(temp)
                    })

                if(img){
                    reader.readAsDataURL(img)
                }

            }
            else{
                var temp = img.name
                add_image_2(temp)
            }
        }
        else{
            add_image_2(temp, flag)
        }
    }

    function add_image_2(img, flag){
        // flag = nastavi kot cover
        // prva slika je cover
        flag = flag || 0
        const wrapper = document.createElement("div")
        wrapper.classList.add("image")
        if(cover){
            if(flag == "flag"){
                wrapper.classList.add("cover_img")
            }
            else if(cover.name == img){
                wrapper.classList.add("cover_img")
            }
        }

        const image = document.createElement("img")
        image.src = img

        // close icon
        const btn = document.createElement("div")
        btn.classList.add("btn_delete")
        btn.title = "Odstrani sliko"
        const c_icon = document.createElement("img")
        c_icon.classList.add("c_icon")
        c_icon.src = "icon/close.png"

        btn.appendChild(c_icon)

        // cover icon
        const btn_cover = document.createElement("div")
        btn_cover.classList.add("btn_cover")
        btn_cover.title = "Nastavi kot ikono"
        const p_icon = document.createElement("img")
        p_icon.classList.add("p_icon")
        p_icon.src = "icon/plus.png"

        btn_cover.appendChild(p_icon)

        const group = document.createElement("div")
        group.classList.add("group")

        wrapper.appendChild(image)
        group.appendChild(btn)
        group.appendChild(btn_cover)
        wrapper.appendChild(group)

        const parent = document.getElementsByClassName("images")
        parent[0].appendChild(wrapper)
    }

    function refresh_images(){
        var image_el = document.getElementsByClassName("image")
        while(image_el.length > 0){
            image_el[0].remove()
        }

        images.forEach(img => {
            add_image(img)
        })

        setTimeout(() => {
            img_tools()	
        }, 500);
    }
    
</script>