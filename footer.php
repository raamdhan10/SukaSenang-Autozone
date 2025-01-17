<!-- tombol chat -->
<a href="#" class="btn btn-primary position-fixed bottom-0 start-0 mb-3 ms-3">
	<i class="fas fa-comment fa-lg"></i>
</a>

<!-- NEWSLETTER -->
<div id="newsletter" class="section">
	<!-- container -->
	<div class="container">
		<!-- row -->
		<div class="row">
			<div class="col-md-12">
				<div class="newsletter">
					<p class="text-uppercase">Subscribe untuk menerima berita terbaru dari kami</p>
					<?php if (isset($subscriberSuccess)) : ?>
						<p><span class="badge bg-success text-white"><i class="fas fa-check-circle"></i>
								Berhasil Subscribe
							</span></p>
					<?php endif; ?>
					<form action="" method="post">
						<input class="input" type="email" name="email" placeholder="Alamat Email">
						<button class="newsletter-btn" type="submit" name="subs"><i class="fa fa-envelope"></i> Subscribe</button>
					</form>
				</div>
			</div>
		</div>
		<!-- /row -->
	</div>
	<!-- /container -->
</div>
<!-- /NEWSLETTER -->

<!-- FOOTER -->
<footer id="footer">
	<!-- top footer -->
	<div class="section">
		<!-- container -->
		<div class="container">
			<!-- row -->
			<div class="row">
			</div>
			<!-- /row -->
		</div>
		<!-- /container -->
	</div>
	<!-- /top footer -->

	<!-- bottom footer -->
	<div id="bottom-footer" class="section">
		<div class="container">
			<!-- row -->
			<div class="row">
				<div class="col-md-12 text-center">
					<span class="copyright">
						<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
						Toko Motor &copy; <script>
							document.write(new Date().getFullYear());
						</script>
						<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
					</span>
				</div>
			</div>
			<!-- /row -->
		</div>
		<!-- /container -->
	</div>
	<!-- /bottom footer -->
</footer>
<!-- /FOOTER -->