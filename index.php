<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Bitter" rel="stylesheet">
        <link rel="stylesheet" href="/css/site.css">
    </head>
    <body>
    <div class="website">
        <div class="gridStyle container">
            <div class="text-center">
                <h4 class="pad-25-top">Nature's Classroom Institute & Montessori School Presents</h4>
                <img src="/images/logo.png" alt="Dinner in the Woods Event Logo">
                <h1>Dinner in the Woods</h1>
                <h5>Live Music · Local Food · Live & Silent Auction · Cabanas</h5>
                <h5>Saturday June 1st, 2019 5:00pm-11:30pm</h5>
            </div>
            <div>
                <form>

                    <h2>Ticket Information</h2>
                    <p>Are you a current Nature's Classroom Institute & Montessori School Family?</p>

                    <div class="form-group">
                        <input type="radio" id="nciFamilyYes" name="nciFamily" value="yes" checked><label for="nciFamilyYes">Yes</label>
                        <input type="radio" id="nciFamilyNo" name="nciFamily" value="yes"><label for="nciFamilyNo">No</label>
                    </div>


                    <div class="form-group">
                        <label for="eventTicketQty">How many tickets would you like to purchase?</label>
                        <p>Purchase eight tickets for reserving a table for $X.XXXXX</p>
                        <input type="number" id="eventTicketQty" name="eventTicketQty" value="0" required> @ $XX per ticket
                    </div>

                    <label for="drinkTicketQty">How many drink tickets would you like?</label>
                    <div><input type="number" id="drinkTicketQty" name="drinkTicketQty" value="0" required> @ $XX per ticket</div>

                    <label for="eggTicketQty">How many egg pull tickets would you like?</label>
                    <div><input type="number" id="eggTicketQty" name="eggTicketQty" value="0" required> @ $XX per ticket</div>

                    <label for="wineTicketQty">How many wine pull tickets would you like?</label>
                    <div><input type="number" id="wineTicketQty" name="wineTicketQty" value="0" required> @ $XX per ticket</div>

                    <label for="additionalContribution">Would you like to make an additional contribution?</label>
                    <div><input type="number" id="additionalContribution" name="additionalContribution" value="0" required></div>

                    <div>Total $XXXX.XX</div>

                    <h2>Billing Information</h2>
                    <label for="firstName">First Name</label>
                    <div><input type="text" id="firstName" name="firstName" value="" required></div>
                    <label for="lastName">Last Name</label>
                    <div><input type="text" id="lastName" name="lastName" value="" required></div>
                    <label for="address">Address</label>
                    <div><input type="text" id="address" name="address" value="" required></div>
                    <label for="city">City</label>
                    <div><input type="text" id="city" name="city" value="" required></div>
                    <label for="state">State
                        <select name="state" id="state">
                            <option value="AL">AL</option>
                            <option value="AK">AK</option>
                            <option value="AR">AR</option>
                            <option value="AZ">AZ</option>
                            <option value="CA">CA</option>
                            <option value="CO">CO</option>
                            <option value="CT">CT</option>
                            <option value="DC">DC</option>
                            <option value="DE">DE</option>
                            <option value="FL">FL</option>
                            <option value="GA">GA</option>
                            <option value="HI">HI</option>
                            <option value="IA">IA</option>
                            <option value="ID">ID</option>
                            <option value="IL">IL</option>
                            <option value="IN">IN</option>
                            <option value="KS">KS</option>
                            <option value="KY">KY</option>
                            <option value="LA">LA</option>
                            <option value="MA">MA</option>
                            <option value="MD">MD</option>
                            <option value="ME">ME</option>
                            <option value="MI">MI</option>
                            <option value="MN">MN</option>
                            <option value="MO">MO</option>
                            <option value="MS">MS</option>
                            <option value="MT">MT</option>
                            <option value="NC">NC</option>
                            <option value="NE">NE</option>
                            <option value="NH">NH</option>
                            <option value="NJ">NJ</option>
                            <option value="NM">NM</option>
                            <option value="NV">NV</option>
                            <option value="NY">NY</option>
                            <option value="ND">ND</option>
                            <option value="OH">OH</option>
                            <option value="OK">OK</option>
                            <option value="OR">OR</option>
                            <option value="PA">PA</option>
                            <option value="RI">RI</option>
                            <option value="SC">SC</option>
                            <option value="SD">SD</option>
                            <option value="TN">TN</option>
                            <option value="TX">TX</option>
                            <option value="UT">UT</option>
                            <option value="VT">VT</option>
                            <option value="VA">VA</option>
                            <option value="WA">WA</option>
                            <option value="WI" selected>WI</option>
                            <option value="WV">WV</option>
                            <option value="WY">WY</option>
                        </select>
                    </label>
                </form>
            </div>
        </div>

    </div>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>
</html>