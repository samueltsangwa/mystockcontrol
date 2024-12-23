    <?php include 'includes/session.php'; ?>
    <?php include 'includes/slugify.php'; ?>
    <?php include 'includes/header.php'; ?>
    <body class="hold-transition skin-blue sidebar-mini">
      <div class="wrapper">
        <!-- Fixed Navbar -->
        <?php include 'includes/navbar.php'; ?>
        
        <!-- Fixed Sidebar Menubar -->
        <?php include 'includes/menubar.php'; ?>

        <!-- Main content area -->
        <div class="content-wrapper" style="padding-top: 60px;"> <!-- Add padding to avoid overlap with navbar -->
          <div class="container-fluid">
            <div class="row justify-content-center">
              <div class="col-md-12"> <!-- Centered column -->
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <i class="glyphicon glyphicon-check"></i> Order Report
                  </div>
                  <div class="panel-body justify-content-center">
                    <form class="form-horizontal" action="getOrderReport.php" method="post" id="getOrderReportForm">
                      <div class="form-group">
                        <label for="startDate" class="col-md-4 control-label">Start Date</label>
                        <div class="col-md-8">
                          <input type="date" class="form-control" id="startDate" name="startDate" placeholder="Start Date" />
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="endDate" class="col-md-4 control-label">End Date</label>
                        <div class="col-md-8">
                          <input type="date" class="form-control" id="endDate" name="endDate" placeholder="End Date" />
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-offset-4 col-md-8">
                          <button type="submit" class="btn btn-success" id="generateReportBtn"> 
                            <i class="glyphicon glyphicon-ok-sign"></i> Generate Report
                          </button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </body>

    <script src="custom/js/report.js"></script>
    <?php require_once 'includes/footer.php'; ?>
