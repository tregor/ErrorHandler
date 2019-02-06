<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript">
	$( document ).ready(function() {
		selectBacktrace(0);
		$("#backtraceList").show();
		$("#backtraceSwitch").click(function(){$("#backtraceList").toggle(300);})
	});


	function selectBacktrace(index) {
		$('.code').hide();
		$('#file'+index).show();
	}
</script>