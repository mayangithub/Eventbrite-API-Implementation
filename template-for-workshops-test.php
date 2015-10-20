<?php
/**
 * Template Name: Workshops AJAX Template 0901
 * Author: Yan Ma
 * Version: Sep 01, 2015
 */
get_header();
?>
<div class="layout-title">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 column col-sm-12 col-md-12 clearfix">
        <h1 class="page-title">Workshops</h1>
      </div>
      <div class="col-lg-12 column col-sm-12 col-md-12 clearfix">
        <div class="st-breadcrumb"><!-- Breadcrumb NavXT 5.1.1 --> 
           Workshops
      	</div>
      </div>
    </div>
  </div>
</div>
<div class="main-wrapper">
	<div class="container main-wrapper-outer">
		<div class="main-wrapper-inner">
			<div class="row" >
				<div class="col-lg-3 col-sm-3 col-md-3 column sidebar  sidebar-left page-has-leftsidebar" id="sidebar">
  					<div class="wrap-sidebar">
					    <?php
						dynamic_sidebar($sidebar);
						?>
  					</div>
				</div>
				<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/Eventbrite.js"></script>
				<div class="col-lg-9 col-sm-9 col-md-9  column main-content page-has-leftsidebar" id="workshops">
					<div class="wrap-primary">
						<div class="use-builder post-1078 page type-page status-publish hentry">
							<div class="section  section-1  lv-1  first last g">
								<div class="bd-row one first last clearfix lv-1">
									<div class="items-wrapper  columns-inside row">
										<div class=" builder-column col-lg-12 col-md-12 col-sm-12 twelve index-1 last  first ">
											<div   class=" no-effect custom-settings   settings-col has-custom alt-bg" >
												<div class="bd-row row-0 n-2 lv-2 first clearfix">
												    <div class="item-inner stpb-heading">
												      <h2 class=" align-default" style=" margin-top:-5px;">Workshops</h2>
												    </div>
												    <!--<div class="clear"></div>-->
												</div>
												<p id="note">Upcoming workshops are listed below. Please register if you plan to attend – this will assure you a spot in the workshop and materials will be prepared for you.</p>
												<div class="bd-row row-1 n-2 lv-2 last clearfix">
													<div class="items-wrapper  items-inside index-2 last">
														<div class="item-inner stpb-text">
															<div  class="item-text-wrapper text-default no-effect" >
																
																<div id="combine">
																	<form class='form-inline form-search' method="post" action="#workshops" id="myform">
																		<div id="organizers">	
																			<select id='org' class='form-control' name="org" onchange="filterByOrg()" >	
																				<option value='all'>All organizers</option>
																			</select>
																		</div>
																		<script type="text/javascript">
																			listOrganizers();
																		</script>
																		<div id="search">
																				<div class="form-group">
																					<input id="searchEvents" type="text" class="form-control" placeholder="Search workshops ..." name="search" />
																				</div>
																				<input type="submit" id='searchsubmit' class='btn btn-default' value="Search" onclick="filterByOrg(); return false; " />
																		</div>
																	</form>
																</div>

																<div id="table">
																	<table>
																		<thead id="head">
																			<tr>
																				<th>Date</th>
																				<th>Workshop</th>
																				<th width="160">Time</th>
																				<th width="140">Organizer</th>
																				<th>Register</th>
																			</tr>
																		</thead>
																		<tbody id="target">																				
																		</tbody>
																		<script type="text/javascript">
																			listAllEvents();
																		</script>
																	</table>
																</div>
																<div id="preload">
																	<img src="<?php bloginfo('template_url'); ?>/assets/images/loading.gif">
																	<label>Searching workshops......</label>
																</div>
															</div>
														</div>
														<div class="clear"></div>
													</div>
												</div>
												<div class="clear"></div>
											</div>
										</div>
										<div class="clear"></div>
									</div>
								</div>
							</div>
						</div>
						<!-- /. end post_class -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<!-- End of Page Container -->


<?php get_footer(); ?>