<?php
/**
 * Template Name: Workshops Template 0824
 * Author: Yan Ma
 * Version: Aug 24, 2015
 */
get_header();
require_once("eventbritephp.php");
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
																<?php 
																	// Instantiate a new object with your OAuth token.
																	$eventbrite = new eventbrite('____________');
																	$userid = '_____________';

																	//get organizers
																	$args = array('id'=>'me', 'data'=>'organizers');
																	$organizers = $eventbrite->users($args, NULL);
																	$orgarray = array();

																?>
																<div id="combine">
																	<form class='form-inline form-search' method="post" action="#workshops" id="myform">
																		<div id="organizers" style="width:40%; float:left;">	
																			<select id='org' class='form-control' name="org" onchange="submitForm()" >	
																				<option value='all'>All organizers</option>
																				<?php 
																					foreach ($organizers['organizers'] as $org) {
																						if (!array_key_exists($org['id'], $orgarray)) {
																							$orgarray[$org['id']] = $org['name'];
																						}
																						echo "<option value='" . $org['id'] . "'";
																						if (isset($_POST['org']) && $_POST['org'] == $org['id']) {
																							echo " selected ";
																						}
																						echo ">";
																						echo $org['name'];
																						echo "</option>";
																					}
																				?>
																			</select>
																		</div>
																		<script type="text/javascript">
																			function submitForm() {
																				document.forms["myform"].submit();
																			}
																		</script>
																		<div id="search" style="float:right;">
																			
																				<div class="form-group">
																					<input id="searchEvents" type="text" class="form-control" placeholder="Search workshops ..." name="search" 
																						<?php 
																							if (isset($_POST['search'])) {
																								echo " value='" . urldecode($_POST['search']) . "' ";
																							}


																						?>
																					/>
																				</div>
																				<input type="submit" id='searchsubmit' class='btn btn-default' value="Search" onclick="submitForm(); " />
																		</div>
																	</form>
																</div>


																<div id="table" style="clear:both;">
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
																		<?php 
																			$events;
																			if (isset($_POST['search']) && strlen($_POST['search']) != 0) {
																				$args = array('data' => 'search');
																				$params = array('q' => urldecode($_POST['search']), 'sort_by' => 'date', 'user.id' => $userid);
																				if (isset($_POST['org']) && $_POST['org'] != 'all') {
																					$params['organizer.id'] = $_POST['org'];
																				}
																				$events = $eventbrite->events($args, $params);
																			} else {
																				$args = array('id' => $userid, 'data' => 'owned_events');
																				$params = array('status' => 'live','order_by' => 'start_asc', 'page' => 1);
																				$events = $eventbrite->users($args, $params);
																			}
																			
																			$page_count = $events['pagination']['page_count'];
																			if ($events['pagination']['object_count'] == 0) {
																				echo "<tr><td colspan='5'>No workshop.</td></tr>";
																			} else { 
																				for ($i = 1; $i <= $page_count; $i++) { 
																					if ($i > 1) {
																						$params['page'] = $i;
																						if (isset($_POST['search']) && strlen($_POST['search']) != 0) {
																							$events = $eventbrite->events($args, $params);
																						} else {
																							$events = $eventbrite->users($args, $params);
																						}
																					}
																					foreach ($events["events"] as $event) {
																						$orgid = $event["organizer_id"];
																						if (isset($_POST['org']) && $_POST['org'] != 'all' && $_POST['org'] != $orgid) {
																							continue;
																						}
																						
																						$date = date('m/d/Y', strtotime($event["start"]["local"]));
																						$starttime = date('g:i A', strtotime($event["start"]["local"]));
																						$endtime = date('g:i A', strtotime($event["end"]["local"]));
																						$title = $event["name"]["text"];

																						// $oarg = array('id' => $orgid);
																						// $org = $eventbrite->organizers($oarg, NULL);
																						$orgname = $orgarray[$orgid];

																						$url = $event["url"];
																						?>
																						<tr>
																							<td><?php echo $date; ?></td>
																							<td><?php echo $title; ?></td>
																							<td><?php echo $starttime; ?> to <?php echo $endtime ?></td>
																							<td><?php echo $orgname; ?></td>
																							<td>
																								<a href='<?php echo $url; ?>' target='_blank'>
																									<img src="https://www.eventbrite.com/custombutton?eid=10869283319" alt="<?php echo $title; ?>" />
																								</a>
																							</td>																					
																						</tr>
																					<?php
																					}																					
																					
																				}
																			}
																		?>
																		</tbody>
																	</table>
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