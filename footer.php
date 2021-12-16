</div> <!-- /wrapper -->

<footer>
	<p>Toko Mutiara Indah <br> <?php echo date("d-M-Y"); ?></p>
</footer>

<script src="js/jquery-3.2.1.js"></script>
<script src="semantic/dist/semantic.min.js"></script>
<script type="text/javascript">
	$('.ui.radio.checkbox')
		.checkbox();
	$('.ui.dropdown')
		.dropdown();
	$('.message .close')
		.on('click', function() {
			$(this)
				.closest('.message')
				.transition('fade');
		});
</script>
</body>

</html>