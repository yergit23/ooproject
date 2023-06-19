<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <title>Test</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-9">
                <div class="row">
                    <div class="col-12">
                        <h1 class="text-center">Elastic Search</h1>
                    </div>
                    <div class="col-12 form-control form-control-lg">
                        <input type="text" class="input-group" id="myInput" onkeyup="search()" placeholder="Search..">
                    </div>
                    <div class="col-12">
                        <ul class="list-group">
                            <li class="list-group-item">Adele</li>
                            <li class="list-group-item">Agnes</li>
                            <li class="list-group-item">Billy</li>
                            <li class="list-group-item">Bob</li>
                            <li class="list-group-item">Calvin</li>
                            <li class="list-group-item">Christina</li>
                            <li class="list-group-item">Cindy</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    // document.querySelector('#elastic').onInput = function() {
    //     let val = this.value.trim();
    //     let elasticItems = document.querySelectorAll('.elastic li');
    //     if(val != '') {
    //         elasticItems.forEach(function(elem) {
    //             if(elem.innerText.search(val) == -1) {
    //                 elem.style.display = 'none';
    //             }
    //             else {
    //                 elem.style.display = '';
    //             }
    //         });
    //     }
    //     else {
    //         elasticItems.forEach(function(elem) {
    //             elem.style.display = '';
    //         });
    //     }
    // }

    // window.onload = () => {
        //     let input = document.querySelector('#js-filter-contacts');
        //     input.oninput = function() {
        //         let value = this.value.trim();
        //         let list = document.getElementById('#js-contacts');

        //         if (value !='') {
        //             list.forEach(elem => {
        //                 if (elem.innerText.search(value) == -1) {
        //                     elem.classList.add('hide');
        //                 }
        //             });
        //         } else {
        //             list.forEach(elem => {
        //                 elem.classList.remove('hide');
        //             });
        //         }
        //     };
        // };

        // function search() {
        //     let input = documemt.getElementById('#elastic').value;
        //     input = input.toLowerCase();
        //     let x = document.getElementByTagName('li');

        //     for (i = 0; i < x.length; i++) {
        //         if (!x[i].innerText.toLowerCase().includes(input)) {
        //             x[i].style.display="none";
        //         } else {
        //             x[i].style.display="";
        //         }
        //     }
        // }

        // document.querySelector('#js-filter-contacts');
        // addEventListener('input', filterList);

        // function filterList() {
        //     const searchInput = document.querySelector('#js-filter-contacts');
        //     const filter = searchinput.value.toLowerCase();
        //     const listItems = document.querySelectorAll('#c_1');

        //     listItems.forEach((item) =>{
        //         let text = item.textContent;
        //         if(text.tolowerCase().includes(filter.toLowerCase())) {
        //             item.style.display = '';
        //         } else {
        //             item.style.display = 'none';
        //         }
        //     });
        // }
        
        // document.querySelector('#js-filter-contacts').onInput = function () {
        //     let val = this.value.trim();
        //     let listItems = document.querySelectorAll('#js-contacts');
        //     if (val != '') {
        //         listItems.forEach(function (elem) {
        //             if (elem.innerText.search(val) == -1) {
        //                 elem.style.display = 'none';
        //             }
        //             else {
        //                 elem.style.display = '';
        //             }
        //         });
        //     }
        // }


        // function myFunction() {
        //     // Declare variables
        //     var input, filter, ul, li, a, i, txtValue;
        //     input = document.getElementById('myInput');
        //     filter = input.value.toUpperCase();
        //     ul = document.getElementById("myUL");
        //     li = ul.getElementsByTagName('li');

        //     // Loop through all list items, and hide those who don't match the search query
        //     for (i = 0; i < li.length; i++) {
        //         a = li[i].getElementsByTagName("a")[0];
        //         txtValue = a.textContent || a.innerText;
        //         if (txtValue.toUpperCase().indexOf(filter) > -1) {
        //             li[i].style.display = "";
        //         } else {
        //             li[i].style.display = "none";
        //         }
        //     }
        // }

        // JavaScript code
    function search() {
        let input = document.getElementById('myInput').value;
        input=input.toLowerCase();
        let x = document.getElementsByClassName('list-group-item');
        
        for (i = 0; i < x.length; i++) { 
            if (!x[i].innerHTML.toLowerCase().includes(input)) {
                x[i].style.display="none";
            }
            else {
                x[i].style.display="";                 
            }
        }
    }

</script>

</html>