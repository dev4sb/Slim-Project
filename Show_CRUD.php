<?php

// print_r($_POST);
// print_r($_FILES);
session_start();
if (empty($_SESSION['user_id'])) {
    header('location:index.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task3</title>
    <link href="css/Show_CRUD.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Azeret+Mono:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@600&display=swap" rel="stylesheet">




    <script type="text/javascript">
        $(document).ready(function() {
            //Dog API Demo
            function changeImage() {
                $.ajax({
                    url: "api/index.php/changeimage",
                    type: "GET",
                    beforeSend: function() {
                            // setting a timeout
                            $(".loader").show();

                        },
                    success: function(data) {
                        $(".loader").hide();
                        let obj = JSON.parse(data);
                        console.log(obj);
                        if (obj.status == "success") {
                            $("#dog-img").attr("src", "" + obj.message + "");
                        } else {

                            $("#dog-img").parent('div').html(obj.message);
                        }


                    }
                });
            }
            //Weather API Demo
            function weather() {
                $.ajax({
                    url: "api/index.php/weather",
                    type: "POST",
                    beforeSend: function() {
                            // setting a timeout
                            $(".loader").show();

                        },
                    success: function(data) {
                        $(".loader").hide();
                        let obj = JSON.parse(data);
                        $('#city').html(obj.location.name);
                        $('#date').html(obj.forecast.forecastday['0'].date);
                        $('#temperature').html(obj.current.temp_c);

                    }
                });

            }

            //Pagination Function
            function pagination(pages, page_no, pg_for, pg_for2) {
                $('#pagination').html("");
                if (pages == 1) {
                    let pagination = "<ul class='pagination pagination-md' data-name='" + pg_for + "' data-name2='" + pg_for2 + " '> </li><li class='page-item disabled'><a class='page-link ' href='#' data-pg='" + (page_no) + "'>" + (page_no) + "</a></li></ul>";
                    $("#pagination").html(pagination);
                } else if (pages == page_no) {
                    let pagination = "<ul class='pagination pagination-md' data-name='" + pg_for + "' data-name2='" + pg_for2 + "'> <li class='page-item'><a class='page-link' href='#' data-pg='" + (page_no - 1) + "'>" + (page_no - 1) + "</a></li></li><li class='page-item disabled'><a class='page-link ' href='#' data-pg='" + (page_no) + "'>" + (page_no) + "</a></li> </ul>";
                    $("#pagination").html(pagination);
                } else if (pages > 1 && page_no == 1) {
                    let pagination = "<ul class='pagination pagination-md' data-name='" + pg_for + "' data-name2='" + pg_for2 + "'> </li><li class='page-item disabled'><a class='page-link ' href='#' data-pg='" + (page_no) + "'>" + (page_no) + "</a></li> <li class='page-item'><a class='page-link' href='#' data-pg='" + (parseInt(page_no) + 1) + "'>" + (parseInt(page_no) + 1) + "</a></li></ul>";
                    $("#pagination").html(pagination);
                } else {
                    let pagination = "<ul class='pagination pagination-md' data-name='" + pg_for + "' data-name2='" + pg_for2 + "'><li class='page-item '><a class='page-link' href='#' tabindex='-1' data-pg='" + (page_no - 1) + "'>" + (page_no - 1) + "</a> </li><li class='page-item disabled'><a class='page-link ' href='#' data-pg='" + (page_no) + "'>" + (page_no) + "</a></li> <li class='page-item'><a class='page-link' href='#' data-pg='" + (parseInt(page_no) + 1) + "'>" + (parseInt(page_no) + 1) + "</a></li></ul>";
                    $("#pagination").html(pagination);
                }
            }
            //Load Data 
            function load(pg_no) {

                let page_no = pg_no;
                if (page_no == null || page_no == undefined || page_no == "") {
                    page_no = 1;
                }
                console.log(page_no == null)
                $.ajax({
                    url: "api/index.php/load-data",
                    type: "POST",
                    data: {
                        pg: page_no
                    },
                    beforeSend: function() {
                        // setting a timeout
                        $(".loader").show();
                    },
                    success: function(data) {
                        $(".loader").hide();
                        $("#table").html("");
                        if (data.status == "false") {
                            let table_row = "<tr class='no-record'><td colspan='10'>No Record Found</td></tr>";
                            $("#table").append(table_row);
                            if ($('.no-record').length == 1) {
                                $("#pagination").html("");
                            }
                        } else {


                            $.each(data, function(key, value) {
                                if (key != "Message") {
                                    if (value.image == null || value.image == undefined || value.image == "") {
                                        let table_row = "<tr><th scope='row' class='checkbox'><input type='checkbox' data-id='" + value.id + "' class='value-checkbox'></th> <th scope='row' class='id' style='display:none;'>" + value.id + "</th><td>" + value.name + "</td><td>" + value.dob + "</td><td >" + value.email + "</td><td>" + value.gender + " </td><td>" + value.hobby + " </td><td>" + value.state + " </td> <td>" + value.subject + "</td><td class='tb-image-bx'> - </td> <td class='act-btn'><button type='button' id='edit-btn' data-toggle='modal' data-target='#myModal' class='btn btn-success' data-id='" + value.id + "'>Edit</button></td><td class='act-btn'><button type='button' id='delete-btn' class='btn btn-danger' data-id='" + value.id + "'>Delete</button></td></tr>";
                                        $("#table").append(table_row);
                                    } else {
                                        let table_row = "<tr><th scope='row' class='checkbox'><input type='checkbox' data-id='" + value.id + "' class='value-checkbox'></th> <th scope='row' class='id' style='display:none;'>" + value.id + "</th><td>" + value.name + "</td><td>" + value.dob + "</td><td >" + value.email + "</td><td>" + value.gender + " </td><td>" + value.hobby + " </td><td>" + value.state + " </td> <td>" + value.subject + "</td><td class='tb-image-bx'><img src='api/Images/" + value.image + "' class='tb-show-img'></td> <td class='act-btn'><button type='button' id='edit-btn' data-toggle='modal' data-target='#myModal' class='btn btn-success' data-id='" + value.id + "'>Edit</button></td><td class='act-btn'><button type='button' id='delete-btn' class='btn btn-danger' data-id='" + value.id + "'>Delete</button></td></tr>";
                                        $("#table").append(table_row);
                                    }

                                    console.log(value);
                                }

                            });


                            let pages = data.Message.pages;

                            pagination(pages, page_no, 'load');



                        }
                    }
                });
            }
            load();


            //Live Search Function
            function live_search(value, page) {
                let page_no = page;
                if (page_no == null || page_no == undefined || page_no == "") {
                    page_no = 1;
                }
                $('#pagination').html("");
                $.ajax({
                    url: "api/index.php/live-search",
                    type: "POST",
                    data: {
                        value: value,
                        pg_no: page_no
                    },
                    beforeSend: function() {
                        // setting a timeout
                        $(".loader").show();
                        $(".loader").hide();
                    },
                    success: function(data) {
                        $(".loader").hide();
                        $("#table").html("");
                        if (data.status == "false") {
                            let table_row = "<tr class='no-record'><td colspan='10'>No Record Found</td></tr>";
                            $("#table").append(table_row);
                            $('#pagination').html("");
                        } else {

                            $.each(data, function(key, value) {
                                if (key != "Message") {
                                    if (value.image == null || value.image == undefined || value.image == "") {
                                        let table_row = "<tr><th scope='row' class='checkbox'><input type='checkbox' data-id='" + value.id + "' class='value-checkbox'></th> <th scope='row' class='id' style='display:none;'>" + value.id + "</th><td>" + value.name + "</td><td>" + value.dob + "</td><td >" + value.email + "</td><td>" + value.gender + " </td><td>" + value.hobby + " </td><td>" + value.state + " </td> <td>" + value.subject + "</td><td class='tb-image-bx'> - </td> <td class='act-btn'><button type='button' id='edit-btn' data-toggle='modal' data-target='#myModal' class='btn btn-success' data-id='" + value.id + "'>Edit</button></td><td class='act-btn'><button type='button' id='delete-btn' class='btn btn-danger' data-id='" + value.id + "'>Delete</button></td></tr>";
                                        $("#table").append(table_row);
                                    } else {
                                        let table_row = "<tr><th scope='row' class='checkbox'><input type='checkbox' data-id='" + value.id + "' class='value-checkbox'></th> <th scope='row' class='id' style='display:none;'>" + value.id + "</th><td>" + value.name + "</td><td>" + value.dob + "</td><td >" + value.email + "</td><td>" + value.gender + " </td><td>" + value.hobby + " </td><td>" + value.state + " </td> <td>" + value.subject + "</td><td class='tb-image-bx'><img src='api/Images/" + value.image + "' class='tb-show-img'></td> <td class='act-btn'><button type='button' id='edit-btn' data-toggle='modal' data-target='#myModal' class='btn btn-success' data-id='" + value.id + "'>Edit</button></td><td class='act-btn'><button type='button' id='delete-btn' class='btn btn-danger' data-id='" + value.id + "'>Delete</button></td></tr>";
                                        $("#table").append(table_row);
                                    }
                                }


                                // console.log(value);
                            });
                            let pages = data.Message.pages;

                            console.log(pages);
                            pagination(pages, page_no, 'live', value);
                        }

                    }

                });


            }

            function inorder(data, orderby, pg_no) {
                let page_no = pg_no;
                let value = data;
                let pg_for = orderby;
                if (page_no == null || page_no == undefined || page_no == "") {
                    page_no = 1;
                }
                $.ajax({
                    url: "api/index.php/orderby",
                    type: "POST",
                    data: {
                        col_name: value,
                        ordrby: pg_for,
                        pg: page_no,
                    },
                    beforeSend: function() {
                        // setting a timeout
                        $(".loader").show();

                    },
                    success: function(data) {
                        $(".loader").hide();
                        $("#table").html("");
                        if (data.status == "false") {
                            let table_row = "<tr class='no-record'><td colspan='10'>No Record Found</td></tr>";
                            $("#table").append(table_row);
                        } else {

                            $.each(data, function(key, value) {
                                if (key != "Message") {
                                    if (value.image == null || value.image == undefined || value.image == "") {

                                        let table_row = "<tr><th scope='row' class='checkbox'><input type='checkbox' data-id='" + value.id + "' class='value-checkbox'></th> <th scope='row' class='id' style='display:none;'>" + value.id + "</th><td>" + value.name + "</td><td>" + value.dob + "</td><td >" + value.email + "</td><td>" + value.gender + " </td><td>" + value.hobby + " </td><td>" + value.state + " </td> <td>" + value.subject + "</td><td class='tb-image-bx'> - </td> <td class='act-btn'><button type='button' id='edit-btn' data-toggle='modal' data-target='#myModal' class='btn btn-success' data-id='" + value.id + "'>Edit</button></td><td class='act-btn'><button type='button' id='delete-btn' class='btn btn-danger' data-id='" + value.id + "'>Delete</button></td></tr>";
                                        $("#table").append(table_row);

                                    } else {

                                        let table_row = "<tr><th scope='row' class='checkbox'><input type='checkbox' data-id='" + value.id + "' class='value-checkbox'></th> <th scope='row' class='id' style='display:none;'>" + value.id + "</th><td>" + value.name + "</td><td>" + value.dob + "</td><td >" + value.email + "</td><td>" + value.gender + " </td><td>" + value.hobby + " </td><td>" + value.state + " </td> <td>" + value.subject + "</td><td class='tb-image-bx'><img src='api/Images/" + value.image + "' class='tb-show-img'></td> <td class='act-btn'><button type='button' id='edit-btn' data-toggle='modal' data-target='#myModal' class='btn btn-success' data-id='" + value.id + "'>Edit</button></td><td class='act-btn'><button type='button' id='delete-btn' class='btn btn-danger' data-id='" + value.id + "'>Delete</button></td></tr>";
                                        $("#table").append(table_row);

                                    }
                                }

                                //console.log(value);
                            });
                            let pages = data.Message.pages;

                            pagination(pages, page_no, orderby, value);
                        }

                    }
                });
            }



            //Change Page
            $(document).on("click", ".page-link", function() {

                let page = $(this).attr("data-pg");
                let padig_for = $('.pagination').attr("data-name");
                let padig_val = $('.pagination').attr("data-name2");
                // console.log(padig_for,padig_val);

                if (padig_for == "load") {

                    load(page);

                } else if (padig_for == "live") {

                    live_search(padig_val, page);

                } else if (padig_for == "asc" || padig_for == "desc") {

                    inorder(padig_val, padig_for, page);

                }

            });

            //Validation
            $("#myModal").validate({
                // Specify the validation rules

                rules: {

                    email: {
                        required: true,
                        email: true,


                    },
                    dob: {
                        required: true,
                        date: true

                    },
                    // file: {
                    //     required: true,

                    // }
                },
                // Specify the validation error messages
                messages: {
                    name: {
                        required: "This Field Is Required *"
                    },
                    email: {
                        required: "This Field Is Required *",
                        email: "Please enter a valid email address *"
                    },
                    dob: {
                        required: "This Field Is Required *"
                    },
                    gender: {
                        required: "This Field Is Required *"
                    },
                    state: {
                        required: "This Field Is Required *"
                    },
                    subjects: {
                        required: "This Field Is Required *"
                    },
                    // file: {
                    //     required: "This  Field Is Required *",
                    //     extension:"Only png ,jpeg and jpg File Is Allowed *",
                    // },
                    hobby: {
                        required: "This Field Is Required *"
                    }

                },
                submitHandler: function(form, event) {
                    event.preventDefault();
                    let fd = new FormData(form);

                    //Array Of Hobby and Subject
                    let hobby = [];
                    let subjects = $('select#Subject').val();

                    //Storing selected value of hobby in Array 
                    $(".hobby").each(function() {
                        if ($(this).is(':checked')) {
                            hobby.push($(this).val());
                        }
                    });


                    //Append hobby and subject value in formdata variable 
                    fd.append("subjects", subjects.join(","));
                    fd.append("hobby", hobby.join(","));

                    console.log("temp");

                    //Insert Data
                    if ($("#myModal").data('id') == 'Insert') { //Insert Data
                        fd.append("action", "insert");
                        fd.append("file", $("#file")[0].files[0]);

                        console.log($("#table").children().length >= 5);
                        $.ajax({ //Insert Ajax Call
                            url: "api/index.php/insert",
                            type: "POST",
                            data: fd,
                            cache: false,
                            processData: false,
                            contentType: false,
                            beforeSend: function() {
                                // setting a timeout
                                $(".loader").show();

                            },
                            success: function(data) {
                                $(".loader").hide();
                                console.log("success");
                                console.log(data);
                                // let obj = JSON.parse(data);

                                if ($('.no-record').length == 1) {
                                    $('tr.no-record').hide();
                                }
                                // Putting - in image column if image value is empty 
                                if ($("#table").children().length < 5) {

                                    if (data.image == null || data.image == undefined || data.image == "") {
                                        let table_row = "<tr><th scope='row' class='checkbox'><input type='checkbox' data-id='" + data.id + "' class='value-checkbox'></th> <th scope='row' class='id' style='display:none;'>" + data.id + "</th><td>" + data.name + "</td><td>" + data.dob + "</td><td >" + data.email + "</td><td>" + data.gender + " </td><td>" + data.hobby + " </td><td>" + data.state + " </td> <td>" + data.subject + "</td><td class='tb-image-bx'> - </td> <td class='act-btn'><button type='button' id='edit-btn' data-toggle='modal' data-target='#myModal' class='btn btn-success' data-id='" + data.id + "'>Edit</button></td><td class='act-btn'><button type='button' id='delete-btn' class='btn btn-danger' data-id='" + data.id + "'>Delete</button></td></tr>";
                                        $("#table").append(table_row);
                                        // console.log(table_row);
                                    } else {
                                        let table_row = "<tr><th scope='row' class='checkbox'><input type='checkbox' data-id='" + data.id + "' class='value-checkbox'></th> <th scope='row' class='id' style='display:none;'>" + data.id + "</th><td>" + data.name + "</td><td>" + data.dob + "</td><td >" + data.email + "</td><td>" + data.gender + " </td><td>" + data.hobby + " </td><td>" + data.state + " </td> <td>" + data.subject + "</td><td class='tb-image-bx'><img src='api/Images/" + data.image + "' class='tb-show-img'></td> <td class='act-btn'><button type='button' id='edit-btn' data-toggle='modal' data-target='#myModal' class='btn btn-success' data-id='" + data.id + "'>Edit</button></td><td class='act-btn'><button type='button' id='delete-btn' class='btn btn-danger' data-id='" + data.id + "'>Delete</button></td></tr>";
                                        $("#table").append(table_row);
                                        // console.log(table_row);
                                    }


                                }
                                // else if ($("#table").children().length == 5) {
                                //     let page_no = $(".page-item.disabled a").attr("data-pg");
                                //     let pagination = "<li class='page-item'><a class='page-link' href='#' data-pg='" + (parseInt(page_no) + 1) + "'>" + (parseInt(page_no) + 1) + "</a></li>";
                                //     $("#pagination ul").append(pagination);
                                // }

                                let page_no = $(".page-item.disabled a").attr("data-pg");
                                load(page_no);

                                $("#suc-msg").html("Inserted Successfully").fadeIn();
                                $("#err-msg").fadeOut();
                                setTimeout(() => {
                                    $("#suc-msg").fadeOut();
                                }, 4000);
                                $("#myModal").trigger("reset");
                                $('.close').trigger('click');


                            }


                        });

                    } else if ($("#myModal").data('id') == 'Update') { //Update Data

                        let ID = $("#update").attr('data-id');
                        fd.append("id", ID);
                        fd.append("action", "update");
                        let form_element = this;
                        fd.append("file", $("#file")[0].files[0]);

                        let element = this;

                        // console.log($("#file")[0].files[0]);

                        $.ajax({ //Update Ajax Call
                            url: "api/index.php/update",
                            type: "POST",
                            data: fd,
                            cache: false,
                            processData: false,
                            contentType: false,
                            beforeSend: function() {
                                // setting a timeout
                                $(".loader").show();

                            },
                            success: function(data) {
                                $(".loader").hide();
                                console.log("success");

                                if (data.status == 'true') {
                                    $("#suc-msg").html("Successfully Updated").fadeIn();
                                    $("#err-msg").fadeOut();
                                    setTimeout(() => {
                                        $("#suc-msg").fadeOut();
                                    }, 4000);


                                    if (data.image == null || data.image == undefined || data.image == "") {


                                        let table_row = "<th scope='row' class='checkbox'><input type='checkbox' data-id='" + data.id + "' class='value-checkbox'></th> <th scope='row' class='id' style='display:none;'>" + data.id + "</th><td>" + data.name + "</td><td>" + data.dob + "</td><td >" + data.email + "</td><td>" + data.gender + " </td><td>" + data.hobby + " </td><td>" + data.state + " </td> <td>" + data.subject + "</td><td class='tb-image-bx'> - </td> <td class='act-btn'><button type='button' id='edit-btn' data-toggle='modal' data-target='#myModal' class='btn btn-success' data-id='" + data.id + "'>Edit</button></td><td class='act-btn'><button type='button' id='delete-btn' class='btn btn-danger' data-id='" + data.id + "'>Delete</button></td>";
                                        console.log(table_row, data.image);
                                        $(".id").each(function() {
                                            let tag_val = $(this).html().toString();
                                            if (tag_val == ID) {
                                                $(this).parent('tr').html(table_row);
                                            }

                                        });

                                    } else {

                                        let table_row = "<th scope='row' class='checkbox'><input type='checkbox' data-id='" + data.id + "' class='value-checkbox'></th> <th scope='row' class='id' style='display:none;'>" + data.id + "</th><td>" + data.name + "</td><td>" + data.dob + "</td><td >" + data.email + "</td><td>" + data.gender + " </td><td>" + data.hobby + " </td><td>" + data.state + " </td> <td>" + data.subject + "</td><td class='tb-image-bx'><img src='api/Images/" + data.image + "' class='tb-show-img'></td><td class='act-btn'><button type='button' id='edit-btn' data-toggle='modal' data-target='#myModal' class='btn btn-success' data-id='" + data.id + "'>Edit</button></td><td class='act-btn'><button type='button' id='delete-btn' class='btn btn-danger' data-id='" + data.id + "'>Delete</button></td>";
                                        console.log(table_row, data.image);
                                        $(".id").each(function() {
                                            let tag_val = $(this).html().toString();
                                            if (tag_val == ID) {
                                                $(this).parent('tr').html(table_row);
                                            }

                                        });

                                    }

                                    // let table_row = "<th scope='row' class='checkbox'><input type='checkbox' data-id='" + data.id + "' class='value-checkbox'></th> <th scope='row' class='id' style='display:none;'>" + data.id + "</th><td>" + data.name + "</td><td>" + data.dob + "</td><td >" + data.email + "</td><td>" + data.gender + " </td><td>" + data.hobby + " </td><td>" + data.state + " </td> <td>" + data.subject + "</td><td class='tb-image-bx'><img src='api/Images/" + data.image + "' class='tb-show-img'></td> <td class='act-btn'><button type='button' id='edit-btn' data-toggle='modal' data-target='#myModal' class='btn btn-success' data-id='" + data.id + "'>Edit</button></td><td class='act-btn'><button type='button' id='delete-btn' class='btn btn-danger' data-id='" + data.id + "'>Delete</button></td>";
                                    // console.log(table_row, data.image);
                                    // $(".id").each(function() {
                                    //     let tag_val = $(this).html().toString();
                                    //     if (tag_val == ID) {
                                    //         $(this).parent('tr').html(table_row);
                                    //     }

                                    // });
                                    // console.log($(this));
                                    $('.close').trigger('click');

                                } else {
                                    $("#err-msg").html("Can't Update The Record").fadeIn();
                                    $("#suc-msg").fadeOut();
                                    setTimeout(() => {
                                        $("#err-msg").fadeOut();
                                    }, 4000);
                                    $('.close').trigger('click');
                                }
                            }


                        });
                    }



                    return false;

                },

            });

            $('.form-control ,.form-check-input ').each(function() {
                $(this).rules('add', {

                    required: true,
                });
            });




            //Delete Data
            $(document).on("click", "#delete-btn", function() {
                if (confirm('Are You Sure You Want to delete this record?')) {
                    let ID = $(this).attr("data-id");
                    $.ajax({
                        url: "api/index.php/delete",
                        type: "POST",
                        data: {
                            id: ID
                        },
                        beforeSend: function() {
                            // setting a timeout
                            $(".loader").show();

                        },
                        success: function(data) {
                            $(".loader").hide();
                            if (data.status == 'true') {
                                $("#suc-msg").html(data.message).fadeIn();
                                $("#err-msg").fadeOut();
                                setTimeout(() => {
                                    $("#suc-msg").fadeOut();
                                }, 4000);

                                $(".id").each(function() {
                                    let tag_val = $(this).html().toString();
                                    if (tag_val == ID) {
                                        $(this).parent('tr').remove().slideUp("slow");
                                        let page_no = $(".page-item.disabled a").attr("data-pg");
                                        let last_page = $(".pagination li:last-child a").attr("data-pg");
                                        if (page_no == last_page) {
                                            load((page_no - 1));
                                        } else {
                                            load(page_no);
                                        }
                                    }

                                    if ($('.no-record').length != "") {
                                        $("#pagination").html("");
                                    }
                                });

                            } else {
                                $("#err-msg").html(data.message).fadeIn();
                                $("#suc-msg").fadeOut();
                                setTimeout(() => {
                                    $("#err-msg").fadeOut();
                                }, 4000);
                            }
                        }
                    });
                }
            });



            //Show Edit Modal Box
            $(document).on("click", "#edit-btn", function() {
                $("#myModal").show();
                $("#modal-footer").hide();
                $("#myModal").data('id', 'Update');
                $("#modal-title").html('Edit Record');

                $("#update-btn").show();


                let ID = $(this).attr("data-id");
                $("#update").attr("data-id", ID);
                $("#preview").show();


                //Ajax Call to fetch single  data
                $.ajax({
                    url: "api/index.php/load-single-data",
                    type: "POST",
                    data: {
                        id: ID
                    },
                    beforeSend: function() {
                        // setting a timeout
                        $(".loader").show();

                    },
                    success: function(data) {
                        $(".loader").hide();
                        console.log(data);
                        console.log("success Modal");
                        data['0'].hobby = (data['0'].hobby).split(',');
                        data['0'].subject = (data['0'].subject).split(',');

                        if (data['0'].image == null || data['0'].image == undefined || data['0'].image == "") {

                            $("#preview").prop('src', '');
                        } else {
                            $("#preview").prop('src', 'api/Images/' + data['0'].image);
                        }


                        console.log(data['0'].image == null);

                        $('select#Subject').val(data['0'].subject);
                        $.each(data['0'], function(key, value) {
                            var ctrl = $('[name=' + key + ']');
                            switch (ctrl.prop("type")) {
                                case "radio":
                                    ctrl.each(function() {
                                        if ($(this).prop('value') == value) $(this).prop("checked", value);
                                    });
                                    break;

                                default:
                                    ctrl.val(value);
                            }


                        });


                    }
                });


            });


            //Hide Edit Modal Box
            $(".close").on("click", function() {
                $("#myModal").hide().trigger("reset");
                $(".hobby:checked").prop("checked", false);
                $(".gender:checked").prop("checked", false);
                $("#preview").hide().prop('src', '');
                $("#update-btn").hide();
                $("#modal-footer").show();
                $("#myModal").data('id', 'Insert');
                $("#modal-title").html('Insert Record');
            });


            // Single CheckBox Click
            $(document).on("click", '.value-checkbox', function() {

                if ($(this).is(':checked')) {

                    $("#delete-all").show();
                    // $(".act-btn,.act-title").hide();

                } else {
                    if ($('.value-checkbox:checked').length == 0) {

                        $("#delete-all").hide();
                        // $(".act-btn,.act-title").show();
                    }
                }
                if ($('.value-checkbox:checked').length == $('.value-checkbox').length) { //checking number of checked checkbox is equal to total number of checkbox

                    $("#title-checkbox").prop('checked', 'checked');

                } else {

                    $("#title-checkbox").prop('checked', '');


                }
            });

            //Title CheckBox
            $("#title-checkbox").on("click", function() {

                if ($(this).is(':checked')) {

                    $(".value-checkbox").prop('checked', 'checked');
                    $("#delete-all").show();
                    // $(".act-btn,.act-title").hide();
                } else {
                    $(".value-checkbox").prop('checked', '');
                    $("#delete-all").hide();
                    // $(".act-btn,.act-title").show();
                }
            });


            //Delete Multiple Value
            $("#delete-all").on("click", function() {
                if (confirm("You Want to Delete This Records?")) {

                    let ID = [];
                    $(".value-checkbox").each(function() {
                        if ($(this).is(':checked')) {
                            ID.push($(this).data('id'));

                        }
                    });
                    console.log(ID);


                    $.ajax({
                        url: "api/index.php/multi-delete",
                        type: "POST",
                        data: {
                            id: ID
                        },
                        success: function(data) {

                            console.log(data);
                            if (data.status == 'true') {



                                let id_array = data.id.split(','); //String TO Array 

                                $(".id").each(function() { //Removing the deleted row
                                    let element = this;
                                    let tag_val = $(this).html().toString();

                                    for (let i = 0; i < id_array.length; i++) {
                                        if (tag_val == id_array[i]) {
                                            $(element).parent('tr').remove().slideUp("slow");
                                            let page_no = $(".page-item.disabled a").attr("data-pg");

                                            load(parseInt(page_no));
                                        }
                                    }
                                });
                                $("#suc-msg").html(data.message).fadeIn();
                                $("#err-msg").fadeOut();
                                setTimeout(() => {
                                    $("#suc-msg").fadeOut();

                                }, 4000);

                                let page_no = $(".page-item.disabled a").attr("data-pg");
                                let last_page = $(".pagination li:last-child a").attr("data-pg");
                                if (page_no == last_page) {
                                    load((page_no - 1));
                                } else {
                                    load(page_no);
                                }


                                //  $("#title-checkbox").prop('checked','');
                                // $("#delete-all").hide();
                                // $(".act-btn,.act-title").show();

                            } else {
                                $("#err-msg").html(data.message).fadeIn(); //Error Messages
                                $("#suc-msg").fadeOut();
                                setTimeout(() => {
                                    $("#err-msg").fadeOut();
                                }, 4000);
                            }
                        }
                    });
                }

            });

            //LogOut
            $("#logout").on("click", function() {
                if (confirm("Are You Sure You Want To Log Out?")) {
                    $.ajax({
                        url: "api/index.php/logout",
                        type: "POST",
                        data: {},
                        beforeSend: function() {
                            // setting a timeout
                            $(".loader").show();

                        },
                        success: function(data) {
                            $(".loader").hide();
                            console.log("success")

                            if (data.status == 'true') {
                                $("#suc-msg").html(data.message).fadeIn(); //Success Message
                                $("#err-msg").fadeOut();
                                setTimeout(() => {
                                    $("#suc-msg").fadeOut();
                                }, 4000);
                                console.log('aaaa');
                                window.location.replace("index.php");
                            } else {
                                $("#err-msg").html(data.message).fadeIn(); //Error Message
                                $("#suc-msg").fadeOut();
                                setTimeout(() => {
                                    $("#err-msg").fadeOut();
                                }, 4000);
                            }

                        }
                    });
                }
            });


            //Live Search On Keyup
            $("#search").on("keyup", function() {

                let value = $(this).val();

                if (value.length > 3) {
                    live_search(value, 1);
                } else {

                    if (value == "") {
                        $("#table").html("");
                        load();
                    }

                }


            });


            //Live Search ON Click
            $("#search-btn").on("click", function() {

                let value = $('#search').val();
                live_search(value, 1);



            });

            //Ascending And Descending
            $(".orderby").on("click", function() {
                $('#pagination').html("");
                let orderby = $(this).attr('data-val');
                let data = $(this).parent("span").siblings('.title').html().toLowerCase();
                console.log()
                inorder(data, orderby, 1);

            });




            changeImage();
            weather();

            //Change Image On Click
            $("#change").on("click", function() {
                changeImage();

            });

        });
    </script>
</head>

<body class="">
    <!-- Button to Open the Modal -->

    <div class="container row p-4 model-btn ">

        <!-- Search Box -->
        <div class="input-group mb-3 col">

            <input type="text" class="form-control search" id="search" aria-label="Default" aria-describedby="inputGroup-sizing-default">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary search-btn" type="button" id="search-btn">Search</button>
            </div>

        </div>
        <div class="col">
            <!-- Insert Button -->
            <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#myModal">
                Insert
            </button>

            <!-- Log Out Button -->
            <button type="button" class="btn btn-primary " class='logout' id='logout'>
                Log Out
            </button>
        </div>


    </div>

    <div class="container">
        <div class='py-2 delete-all-btn'>
            <button type="button" class="btn btn-danger delete-all " style='display:none;' id="delete-all">Delete All</button>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col"><input type="checkbox" id="title-checkbox"></th>
                    <th scope="col"> <span class="title">Name</span> <span class="asc-dsc-btn"><i class="fas fa-caret-up orderby" data-val="asc"></i><i class="fas fa-caret-down orderby" data-val="desc"></i></span></th>
                    <th scope="col"> <span class="title">DOB</span> <span class="asc-dsc-btn"><i class="fas fa-caret-up orderby" data-val="asc"></i><i class="fas fa-caret-down orderby" data-val="desc"></i></span></th>
                    <th scope="col"> <span class="title">Email</span> <span class="asc-dsc-btn"><i class="fas fa-caret-up orderby" data-val="asc"></i><i class="fas fa-caret-down orderby" data-val="desc"></i></span></th>
                    <th scope="col"> <span class="title">Gender </span> <span class="asc-dsc-btn"><i class="fas fa-caret-up orderby" data-val="asc"></i><i class="fas fa-caret-down orderby" data-val="desc"></i></span></th>
                    <th scope="col"> <span class="title">Hobby</span><span class="asc-dsc-btn"><i class="fas fa-caret-up orderby" data-val="asc"></i><i class="fas fa-caret-down orderby" data-val="desc"></i></span></th>
                    <th scope="col"> <span class="title">State</span> <span class="asc-dsc-btn"><i class="fas fa-caret-up orderby" data-val="asc"></i><i class="fas fa-caret-down orderby" data-val="desc"></i></span></th>
                    <th scope="col"> <span class="title">Subject</span> <span class="asc-dsc-btn"><i class="fas fa-caret-up orderby" data-val="asc"></i><i class="fas fa-caret-down orderby" data-val="desc"></i></span></th>
                    <th scope="col">Image</th>
                    <th scope="col" colspan="2" class="act-title">Action</th>
                </tr>
            </thead>
            <tbody id="table">

            </tbody>
        </table>

        <nav aria-label="..." id="pagination">
            <!-- <ul class="pagination pagination-md">
                <li class="page-item ">
                    <a class="page-link" href="#" tabindex="-1" data-pg="">1</a>
                </li>
                <li class="page-item disabled"><a class="page-link " href="#" data-pg="">2</a></li>
                <li class="page-item"><a class="page-link" href="#" data-pg="">3</a></li>
            </ul> -->
        </nav>


    </div>

    <hr>
    <div class="container section-1">
        <h3>
            Refresh A Page Or Select A Dog Category And See An Image.
        </h3>
        <div>
            <img id="dog-img">
        </div>
        <div class="change-btn-bx">
            <button type="button" class="btn bg-primary text-white" id="change">View Dog</button>
        </div>
    </div>
    <div class="container section-2 py-5">
        <div class="card p-4 ">
            <div class="">

                <h3 id="city"></h3>
                <h3 id="date"></h3>

            </div>
            <div class="temperature">
                <span id="temperature"></span><sup>o</sup>C
            </div>
        </div>
    </div>


    <!-- The Modal -->
    <form class="modal" id="myModal" tabindex="-1" method="POST" enctype="multipart/form-data" data-id="Insert" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">Insert Record</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <!-- Full Name -->
                    <div class="">
                        <label>Full Name:</label>
                        <input type="text" class="form-control" id="name" autocomplete="off" placeholder="Enter Name" name="name" required="required">

                    </div>
                    <!-- DOB -->
                    <div class="">
                        <label>Date Of Birth:</label>
                        <input type="date" class="form-control" id="dob" placeholder="DOB" name="dob" required="required" min="2005-01-01">
                    </div>
                    <!-- Email -->
                    <div class="">
                        <label>Email:</label>
                        <input type="mail" class="form-control" id="email" autocomplete="off" placeholder="Enter email" name="email">
                    </div>
                    <!-- Password -->
                    <!-- <div class="">
                        <label>Password:</label>
                        <input type="password" class="form-control" placeholder="Enter password" name="pswd" id="pswd" required="required">
                    </div> -->
                    <!-- Gender -->
                    <div class="">
                        <label>Gender:</label>
                        <input type="radio" class="form-check-input gender" name="gender" value="Male">Male
                        <input type="radio" class="form-check-input gender" name="gender" value="Female">Female
                        <label for="gender" class="error" style="display:none;"></label>
                    </div>
                    <!-- Hobby -->
                    <div class="">
                        <label>Hobby:</label>
                        <input type="checkbox" class="form-check-input hobby" name="hobby" value="Gaming">Gaming
                        <input type="checkbox" class="form-check-input hobby" name="hobby" value="Cricket">Cricket
                        <input type="checkbox" class="form-check-input hobby" name="hobby" value="Reading">Reading
                        <label for="hobby" class="error" style="display:none;"></label>
                    </div>
                    <!-- State -->
                    <div class="">
                        <label>State:</label>
                        <select class="form-control" id="State" name="state">
                            <option value="" selected disabled>Select State</option>
                            <option value="Gujarat">Gujarat</option>
                            <option value="Mumbai">Mumbai</option>
                            <option value="Rajasthan">Rajasthan</option>
                            <option value="Delhi">Delhi</option>
                        </select>
                        <label for="state" class="error" style="display:none;"></label>
                    </div>
                    <!-- Subjects -->
                    <div class="">
                        <label>Subjects:</label>
                        <select class="form-control" id="Subject" name="subjects" multiple="multiple">
                            <option value="" selected disabled>Select Subject</option>
                            <option value="Chemistry">Chemistry</option>
                            <option value="Computer">Computer</option>
                            <option value="Physics">Physics</option>
                            <!-- <option value="Maths">Maths</option> -->
                        </select>
                    </div>


                    <!-- Image Preview -->
                    <div class="image-box">
                        <img src="" class="preview" id="preview" alt="">
                    </div>

                    <!-- Image -->
                    <div>
                        <input type="file" class="form-control-file border" id="file" name="file" accept="image/png, image/jpg, image/jpeg">
                    </div>



                </div>

                <!-- Update Button -->
                <div class="modal-footer" id="update-btn">
                    <button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Update" id="update" name="submit" class="Update">
                </div>
                <!-- Modal footer -->
                <div class="modal-footer" id="modal-footer">
                    <button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary " value="Submit" id="submit" name="submit">
                    <!-- <button type="submit" class="btn btn-primary" id="submit" name="">Submit</button> -->
                </div>

            </div>
        </div>
    </form>




    <div class="err-msg" id="err-msg"></div>
    <div class="suc-msg" id="suc-msg"></div>
    <div class="loader">
        <div></div>
    </div>
</body>

</html>