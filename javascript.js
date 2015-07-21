$(document).ready(function () {
    $("#menu a").hover(
        function () {
            $(this).stop().animate({backgroundColor: "#e83155"}, "slow");
        }, function () {
            $(this).stop().animate({backgroundColor: "#4b4b4b"}, "fast");
        });
});

$(document).ready(function () {
    $(".comment-btn, .more-btn, #comment-form td input[type=submit], .comment-footer span, #login-form td input[type=submit]").hover(
        function () {
            $(this).stop().animate({marginTop: "-3px"}, "fast");
        }, function () {
            $(this).stop().animate({marginTop: "0px"}, "fast");
        });
});

$(document).ready(function () {
    $("#category ul li a, #last-post ul li a").hover(
        function () {
            $(this).stop().animate({paddingRight: "60px"}, "slow");
        }, function () {
            $(this).stop().animate({paddingRight: "40px"}, "fast");
        });
});

$(document).ready(function () {
    $("#login-form a").hover(
        function () {
            $(this).stop().animate({color: "#e83155"}, "slow");
        }, function () {
            $(this).stop().animate({color: "#4b4b4b"}, "fast");
        });
});

function answer_to(comment_id, full_name) {
    var hidden = document.getElementById("answer_id");
    hidden.value = comment_id;
    var p = document.getElementById("comment-form-header");
    p.innerHTML = 'در پاسخ به&nbsp;<span style="color:#4b4b4b;">' + full_name + '</span>';
    p.innerHTML += "&nbspدیدگاه خود را بنویسید.";
    cancel = document.createElement("span");
    cancel.innerHTML = "لغو پاسخ";
    cancel.style.backgroundColor = "#3142e8";
    cancel.style.borderRadius = "5px";
    cancel.style.color = "#f4f4f4";
    cancel.style.padding = "5px 8px";
    cancel.style.margin = "0px 20px";
    cancel.style.cursor = "pointer";
    cancel.style.fontSize = "16px";
    cancel.style.fontFamily = "'Koodak-Bold'";
    cancel.addEventListener('click', function () {
        var hidden = document.getElementById("answer_id");
        hidden.value = 0;
        var p = document.getElementById("comment-form-header");
        p.innerHTML = "دیدگاه خود را در مورد این مطلب بنویسید.";
    });
    p.appendChild(cancel);
    location.href = "#comment-location";
}