<head>
    <title>PHP HTML Testing</title>
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <style type="text/css">
        .brand{
            background: #E27D60 !important;
        }
        .buttonColor{
            background: #41B3A3 !important;
        } 
        .headerbackground2{
            background: #CA7157 !important;
        }
        .brand-text{
            color: #1A1113  !important;
        }
        form{
            max-width: 460px;
            margin: 20px auto;
            padding: 20px;
        }
        .navbar{
            height: 800px; /* Just an example height*/
        }
        form label{
            font-weight:bold;
            color: #1A1113  !important;
        }
    </style>

</head>
<body class="grey lighten-4">
    <nav class="nav-extended">
    <div class="nav-wrapper brand">
      <a href="/" class="brand-logo"><b>Database Class</b></a>
        <ul id="nav-mobile" class="right hide-on-small-and-down">
            <li><a href="createB2.php" class="btn buttonColor z-depth-0">Create B-2</a></li>
        </ul>
        <ul id="nav-mobile" class="right hide-on-small-and-down">
            <li><a href="createW2.php" class="btn buttonColor z-depth-0">Create W-2</a></li>
        </ul>
        <ul id="nav-mobile" class="right hide-on-small-and-down">
            <li><a href="createTaxReturn.php" class="btn buttonColor z-depth-0">Create Tax Return</a></li>
        </ul>
    </div>
    <div class="nav-content headerbackground2">
      <ul class="tabs tabs-transparent">
        <li class="tab col s3"><a class="active" href="user.php">User Forms</a></li>
        <li class="tab col s3"><a class="active" href="company.php">Company Forms</a></li>
        <li class="tab col s3"><a class="active" href="other.php">Management</a></li>
      </ul>
    </div>
  </nav>

