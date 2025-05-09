<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!empty($_SESSION['alert'])) {
  $alertType = $_SESSION['alert']['type'];
  $message = $_SESSION['alert']['message'];

  echo "
  <div class='alert alert-$alertType alert-dismissible fade show' role='alert' id='alertBox'>
      $message
      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
          <span aria-hidden='true'>&times;</span>
      </button>
  </div>
  <script>
      // Use JavaScript to remove the alert after 30 seconds
      setTimeout(function() {
          var alertBox = document.getElementById('alertBox');
          if (alertBox) {
              alertBox.classList.remove('show'); // Hide the alert
              alertBox.classList.add('fade');   // Ensure fade-out effect
              setTimeout(function() {
                  alertBox.remove(); // Remove the alert from the DOM
              }, 500); // Allow fade-out animation time
          }
      }, 10000); // 10 seconds
  </script>
  ";

  unset($_SESSION['alert']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Invisible Intercom | Registration Page</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="css/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="css/adminlte.min.css">
  <!-- Custom Style -->
  <link rel="stylesheet" href="css/register.css">
</head>

<body class="hold-transition register-page">
  <div class="register-box">
    <div class="register-logo">
      <a href="index.php"><b>Invisible</b>Intercom</a>
    </div>

    <div class="card">
      <div class="card-body register-card-body">
        <p class="login-box-msg">Welcome to Your Home</p>

        <form id="registerForm" action="include/auth/signup.php" method="post">
          <div class="input-group mb-3">
            <input type="text" name="fname" class="form-control" placeholder="First Name" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="text" name="lname" class="form-control" placeholder="Last Name" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="mobphone" name="mobphone" class="form-control" oninput="formatPhoneNumber(this)" onpaste="handlePaste(event)" placeholder="Mobile Phone" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-mobile-button"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="homphone" name="homphone" class="form-control" oninput="formatPhoneNumber(this)" onpaste="handlePaste(event)" placeholder="Home Phone Optional">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-phone"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="pwd" id="pwd" class="form-control" placeholder="Password" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
          </div>
          <small id="passwordHelp" class="form-text text-muted">
              Password must be at least 8 characters, include 1 uppercase letter, 1 number, and 1 special character.
          </small>
          <div class="input-group mb-3">
              <progress id="passwordStrength" max="100" value="0" class="w-100"></progress>
              <span id="passwordStrengthText" class="password-strength-text"> </span>
          </div>

          <div class="input-group mb-3">
              <input type="password" name="pwdc" id="pwdc" class="form-control" placeholder="Retype password" required>
              <div class="input-group-append">
                  <div class="input-group-text">
                      <span class="fas fa-lock"></span>
                  </div>
              </div>
          </div>

          <div id="passwordError" class="text-danger mb-3" style="display: none;"></div>

          <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
          <div class="row">
            <div class="col-8">
              <div class="icheck-primary">
                <input type="checkbox" id="agreeTerms" name="terms" value="agree" required>
                <label for="agreeTerms">
                  I agree to the <a href="#" data-toggle="modal" data-target="#termsModal">terms</a>
                </label>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-4">
              <button type="submit" name="submit" class="btn btn-primary btn-block">Register</button>
            </div>
            <!-- /.col -->
          </div>
        </form>

        <div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">TERMS AND CONDITIONS Effective Date: December 4, 2024</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <!-- Terms and Conditions Text Here -->
                <p>
                    Welcome to Invisible Intercom! These Terms and Conditions ("Terms") govern your use of the Invisible Intercom
                  platform, including its software, services, and hardware offerings/integrations ("Services"). Invisible Dev Group
                  LLC ("we," "us," or "our") provides this innovative access control solution to manage gates, doors, and other entry
                  points for virtually any type of property such as single-family homes, multifamily locations, commercial buildings,
                  and parking areas. By using the Services, you agree to be bound by these Terms. If you do not agree, you must not
                  use the Services.
                </p>
                  1. Acceptance of Terms:
                    1. By using Invisible Intercom, you confirm that:
                    2. You are at least 18 years old or are under 18 but have obtained the consent of a parent or legal guardian to use the Services.
                    3. If you are a parent or guardian permitting a minor to use the Services, you agree to these Terms on their behalf and accept full responsibility for the minor’s use of the platform, including all associated risks.
                    4. You have the authority to agree to these Terms on behalf of yourself or the entity you represent.
                  2. Scope of Services:  Invisible Intercom provides a combination of software and cloud-based services that directly interact with physical electronic security systems, including gates, doors, and perimeter access controls and surveillance systems. The Services include but are not limited to:
                    1. Visitor access management via QR codes, phone numbers, phone calls, and keypad codes.
                    2. Remote unlocking of gates and doors by authorized users.
                    3. Communication with authorized points of contact ("APOC").
                    4. Integration with new and existing systems such as access control, i/o, surveillance hardware and/or software.
                  3. User Responsibilities:  Invisible Intercom empowers users to manage access to their property, including granting temporary or permanent access to visitors through phone calls, keypad codes, or other means. By using the platform, you agree to the following responsibilities:
                    1. Compliance with Local Laws: You are responsible for ensuring that your use of Invisible Intercom complies with all local, state, and federal laws governing security systems and access control, including any notification or consent requirements for recording calls or monitoring activity including laws related to privacy, property access, and visitor interactions.
                    2. Authorized Use Only: You agree to use the Services solely for legitimate and lawful purposes. You must not:
                      1. Share access credentials (e.g., keypad codes) with unauthorized individuals.
                      2. Grant access to individuals who have not been authorized by the property owner or manager.
                      3. Use the platform to bypass any security or access restrictions.
                      4. Tamper with, modify, or reverse-engineer the platform or its integrations.
                    3. Accountability for Granting Access: When granting access to visitors, including delivery drivers, contractors, or other third parties:
                      1. Verification: It is your responsibility to verify the identity and purpose of the visitor before granting access.
                      2. Temporary Visitors: You are fully responsible for all actions taken by individuals you allow access to, including strangers, delivery drivers, or contractors.
                      3. Keypad Code Use:
                        - If you create and distribute keypad codes, you acknowledge that these codes can be shared or misused by others.
                        - You are responsible for monitoring and revoking any codes that are no longer needed or have been compromised.
                      4. Visitor Conduct: Invisible Intercom does not vet visitors or monitor their actions after access is granted. You assume full liability for the behavior and actions of anyone granted access through the system.
                      5. You are responsible for maintaining the security of your account credentials and hardware integrations.
                    4. Proper Use of Keypad Codes and Magic Password referred to as "code/s": codes are a convenient and flexible way to grant property access. However, their use carries risks:
                      1. Responsibility for Code Sharing: You are responsible for any actions taken using a code you have created or distributed.
                      2. Code Expiration: We recommend setting time-limited or single-use codes for temporary visitors to minimize the risk of unauthorized access.
                      3. Monitoring Codes: You must monitor active codes regularly and deactivate those that are no longer necessary.
                    5. Security of Access Methods: 
                      1. Safeguard Access Credentials: You must protect your account credentials, keypad codes, and any other access methods from unauthorized use.
                      2. Report Unauthorized Access: You must notify us immediately if you suspect unauthorized use of your account or a breach of your property’s access control system.
                    6. Risk Acknowledgment: By using the platform, you acknowledge and accept the risks associated with granting access to third parties, including:
                      1. Stranger Access: The platform may facilitate access for delivery drivers, contractors, or unknown individuals, and you are responsible for ensuring the security and appropriateness of such access.
                      2. Unauthorized Use: While the platform provides tools to monitor and manage access, Invisible Intercom cannot guarantee that access will always be limited to intended individuals.
                  4. Hardware and Third-Party Integrations: 
                    1. a. Hardware Compatibility: Invisible Intercom works with certain physical security systems, including electronic gates and doors controllers. You are responsible for ensuring the compatibility and proper installation of these systems with the platform.
                    2. Third-Party Responsibility: Any third-party hardware or software integrations are the responsibility of the property owner or manager. We are not liable for malfunctions, improper installations, or failures of third-party systems.
                  5. Access Control Functionality:
                    1. User Flow: The platform enables property access via QR codes, phone calls, or keypad codes.- Visitors must call or scan the QR code displayed at entry points.- Authorized codes or commands trigger the unlocking of gates or doors.
                    2. Call Handling: Calls are routed to an APOC, who may grant or deny access remotely. APOCs are responsible for verifying visitor identity before granting access.
                  6. ADA Compliance:  Invisible Intercom is committed to ensuring accessibility for all users, including those with disabilities. You agree to comply with applicable accessibility laws, including but not limited to the Americans with Disabilities Act (ADA), when using the Services.
                    1. Accessible Signage: If you install signage (e.g., QR code or phone number signs), you are responsible for ensuring the signage meets ADA requirements, including visibility, height, and readability for individuals with disabilities. Since invisible intercom can be installed on any type of entry point, custom signs can be created to meet any of the customers needs. If a custom sign is to be developed to comply with a properties needs, please contact your installer or local sign shop.
                    2. Device Accessibility: Invisible Intercom provides features designed to be accessible to users with disabilities (e.g., keypad access for visually impaired users). You agree to use and implement these features responsibly to ensure compliance.
                    3. Responsibility for Compliance: Property owners and managers are solely responsible for ensuring that any installation and use of the system comply with accessibility laws applicable to their location.
                  7. Contractors and Installation:  The proper installation and maintenance of the Invisible Intercom system are critical to its functionality. The following terms apply to contractors and installation:
                    1. Certified Installers: The installation of hardware components (e.g., gates, doors, and access systems) must be performed by certified or licensed professionals where required by state or local law.
                    2. Property Owner Responsibility: Property owners and managers are responsible for selecting qualified contractors and ensuring proper installation of the system.
                    3. No Warranty on Installation: Invisible Intercom does not guarantee the quality of work performed by third-party contractors. Any damages or issues arising from improper installation are solely the responsibility of the property owner or contractor.
                    4. Maintenance and Compatibility: You are responsible for maintaining the physical hardware connected to the Invisible Intercom system and ensuring its compatibility with our Services.
                  8. Privacy and Data Security: 
                    1. Data Collection: Invisible Intercom collects data related to access events, such as call logs, codes entered, and access granted or denied. This data is used solely for access control purposes and to improve our Services.
                    2. Data Protection: We take reasonable measures to protect user data. However, you acknowledge that no system is completely secure, and we are not liable for unauthorized access to your data.
                    3. Recording Disclosure: If call recordings are enabled, you must notify visitors in accordance with applicable recording laws.
                  9. Liability Disclaimer:
                    1. Security Breaches: While Invisible Intercom facilitates access control, we are not responsible for any unauthorized access, security breaches, or misuse of the platform.
                    2. System Malfunctions: We are not liable for damages caused by system malfunctions, hardware failures, or incorrect installations of third-party equipment.
                    3. Visitor Conduct: We are not responsible for the actions of individuals granted access through the platform. Property owners and managers assume all risk and liability for granting access.
                  10. Insurance Considerations:  Invisible Intercom does not provide insurance coverage for any property, damages, or liabilities arising from the use of the system. By using the Services, you acknowledge and agree that:
                    1. Property Insurance: Property owners and managers are responsible for maintaining appropriate insurance coverage, including liability and property insurance, to protect against potential risks associated with granting access to visitors.
                    2. No Liability for Unauthorized Access: Invisible Intercom is not liable for any unauthorized access, property damage, or personal injury resulting from the use or misuse of the system.
                    3. Risk Mitigation: Users are encouraged to take appropriate measures, including using temporary codes or monitoring access logs, to minimize risks associated with granting access to third parties.
                  11. Service Availability:  We strive to provide uninterrupted access to our platform. However, we do not guarantee that the Services will always be available, error-free, or compatible with all hardware systems. We reserve the right to update, suspend, or discontinue the Services at any time.
                  12. FCC Compliance:  Invisible Intercom complies with all applicable Federal Communications Commission (FCC) regulations governing telecommunications and electronic devices. By using the Services, you acknowledge and agree that:
                    1. You are responsible for ensuring compliance with local telecommunication laws, including any additional requirements for using call-based or electronic access systems.
                    2. Invisible Intercom is not liable for any fees, fines, or penalties arising from non-compliance with telecommunication or regulatory requirements in your jurisdiction. 
                    3. If you have questions regarding FCC regulations or compliance, consult with a qualified professional before using the system.
                  13. Indemnification:  You agree to indemnify, defend, and hold harmless Invisible Dev Group LLC and its affiliates from any claims, damages, or liabilities arising from:
                    1. Your use of the Services.
                    2. Unauthorized access granted through your account or hardware.
                    3. Violations of local access control laws or regulations.
                  14. Termination of Service:  We reserve the right to terminate your access to the platform if you violate these Terms or use the Services in a way that jeopardizes system security or violates applicable laws.
                  15. Governing Law and Arbitration:  
                    1. Governing Law: These Terms are governed by the laws of Virginia, without regard to its conflict of laws principles.
                    2. Arbitration: Any disputes arising under these Terms shall be resolved through binding arbitration in Fairfax County, Virginia, unless prohibited by law.
                  16. Modifications to Terms:  We may update these Terms at any time. Changes will take effect immediately upon posting. Your continued use of the Services constitutes acceptance of the revised Terms.
                  17. Contact Information:  If you have questions about these Terms, please contact us at:Email: [Insert Email Address]Phone: [Insert Phone Number]
                </p>
                <p>
                  By using Invisible Intercom, you agree to these Terms and Conditions. Thank you for trusting us to manage your visitor and access control needs!
                </p>
                <!-- Add more text as needed -->
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <a href="login.php" class="text-center">I already have a membership</a>
      </div>
      <!-- /.form-box -->
    </div><!-- /.card -->
    <?php
    if (isset($_GET["error"])) {
      if ($_GET["error"] == "emptyinput") {
        echo "<p>Please Fill in all Fields!</p>";
      } else if ($_GET["error"] == "invalidemail") {
        echo "<p>Email is Not Valid!</p>";
      } else if ($_GET["error"] == "passwordsdonotmatch") {
        echo "<p>Passwords Do Not Match!</p>";
      } else if ($_GET["error"] == "emailalreadyexists") {
        echo "<p>This Email Has Already Been Registered!<br>Please Login Instead!</p>";
      }
    }
    ?>
  </div>
  <!-- /.register-box -->

  <!-- js -->
  <script src="js/register.js"></script>
  <!-- jQuery -->
  <script src="js/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="js/adminlte.min.js"></script>
</body>

</html>