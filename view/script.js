
$( document ).ready(function() {
	selectBacktrace(0);
	$("#backtraceList").show();
	$("#backtraceSwitch").click(function(){$("#backtraceList").toggle(300);})
});


function selectBacktrace(index) {
	$('.code').hide();
	$('#file'+index).show();
}