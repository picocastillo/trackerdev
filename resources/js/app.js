/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


require('./components/Home/App');
require('./components/Iteration');
require('./components/TableTasks');
require('./components/ItemsTask');
require('./components/MessageTask');


import AOS from 'aos';
import 'aos/dist/aos.css'; // You can also use <link> for styles
// ..
AOS.init();


//Home mananger

if (document.getElementById('select_project')) {

    $(document).ready(function(){
        $("#select_project").change(function(){
            var id = $(this).children("option:selected").val();
            $('#go_to_project').attr("href",`manager/iteration/${id}`);
            $('#go_to_edit_project').attr("href",`manager/iteration/edit/${id}`);
            $('#input_deposit_project').attr('value',id);
        });
    });
}




    $(document).ready(function(){
        const n = window.location.href.split('/');
        if (n.length==4 && (n[3]==="methodology" || n[3]==="" || n[3]==="contact" || n[3]==="projects")){
            document.body.style.overflowY = "hidden";
        }
    });

