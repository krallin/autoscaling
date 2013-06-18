<?php
include_once("constants.php");
include_once("functions.php");
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Custom Metric App</title>
    <link type="text/css" href="/static/css/bootstrap.css" rel="stylesheet">
    <link type="text/css" href="/static/css/bootstrap-responsive.css" rel="stylesheet">
    <link type="image/x-icon" href="/static/img/favicon.ico" rel="shortcut icon" />
    <style type="text/css">code{color:#15d;} code.error{color:#d14}.hero-unit img{margin-right:10px;margin-top:10px}</style>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>
    <div class="hero-unit">
      <img src="/static/img/scalr-logo.png" alt="Scalr Logo" class="pull-left"/>
      <h1>Custom Metric App</h1>
      <p>This is the Custom Metric demo app.</p>
      <p>This lets you simulate and monitor the "CPU Cache Line activity" on this server.</p>
      <p>This is a per-server metric, so its value might be different on each of your servers!</p>
      <small class="muted">
        Note that this is all fictional: CPU Cache Line activity isn't really monitored, and wouldn't
        really make sense for autoscaling!
      </small>
    </div>
    <div class="container">
<?
if (METHOD === "GET") {
?>
      <h2>Current Value of the Metric</h2>
      <p>CPU Cache Line Activity is currently at:
        <span class="badge badge-info"><b><?php echo read_metric(); ?></b></span>
      </p>
      <small class="muted">This value will be used by Scalr for autoscaling.</small>

      <h2>Simulate a different level:</h2>
      <form action="/" method="post" class="form-inline">
        <fieldset>
          <div class="input-append">
            <input autofocus="autofocus" type="text" name="value" placeholder="New Value..."/>
            <input type="submit" class="btn btn-success" value="Submit it!"/>
          </div>
        </fieldset>
      </form>

      <hr/>

      <h2>Configuring autoscaling</h2>
      <p>To make sure your app autoscales based on this CPU Cache Line Activity, make sure that you defined
         a file-based custom metric that uses <code><? echo METRIC_PATH; ?></code> as data source.</p>
<?
} elseif (METHOD === "POST") {
  $value = $_POST["value"];
  if (is_numeric($value)) {
    write_metric((float) $value);
    header("Location: /");
  } else { ?>
      <div class="alert alert-error">
        This value ("<? echo $value; ?>") is invalid.
      </div>
      <div class="text-center">
        <a href="/" class="btn btn-large btn-primary" type="button">Please try again!</a>
      </div>
      <hr/>
      <div>
        <small class="muted">Hint: You should use a number for the metric!</small>
      </div>
<?
  }
} else {
  echo "Unsupported method! (Try GET!)";
}
?>
    </div>
  </body>
</html>
