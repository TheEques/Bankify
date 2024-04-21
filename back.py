from flask import Flask, render_template, redirect, request, url_for, session
import subprocess
import mysql.connector
import pickle
import pymysql
import json

app = Flask(__name__)
app.secret_key = b'_5#y2L"F4Q8z\n\xec]/'

# Load your machine learning model from a .pkl file using pickle
with open(r"C:\xampp\htdocs\Bankify1\trained_model\model.pkl", 'rb') as model_file:
    model = pickle.load(model_file)

# Function to connect to MySQL database
# def connect_to_database():
#     return mysql.connector.connect(
#         host="localhost",
#         user="root",
#         password="",
#         database="bankify"
#     )


@app.route('/predict', methods=['POST'])
def predict():
    if request.method == 'POST':
        # Get user input from the form
        gender = float(request.form['gender'])
        married = float(request.form['married'])
        dependents = float(request.form['dependents'])
        education = float(request.form['education'])
        self_employed = float(request.form['self_employed'])
        applicant_income = float(request.form['applicant_income'])
        coapplicant_income = float(request.form['coapplicant_income'])
        loan_amount = float(request.form['loan_amount'])
        loan_amount_term = float(request.form['loan_amount_term'])
        credit_history = float(request.form['credit_history'])
        property_area = float(request.form['property_area'])

        # Create a dictionary with the input data
        input_data = {
            'Gender': gender,
            'Married': married,
            'Dependents': dependents,
            'Education': education,
            'Self_Employed': self_employed,
            'ApplicantIncome': applicant_income,
            'CoapplicantIncome': coapplicant_income,
            'LoanAmount': loan_amount,
            'Loan_Amount_Term': loan_amount_term,
            'Credit_History': credit_history,
            'Property_Area': property_area
        }

        # Make a prediction using the model
        prediction = model.predict([list(input_data.values())])

        # Convert the prediction to a user-friendly message
        if prediction[0] == 1:
            result = "Approved"
            property_area_text = "Rural" if property_area == 0 else ("Semi-Urban" if property_area == 1 else "Urban")

            return render_template('approved.html', gender="Male" if gender == 1 else "Female", married="Married" if married == 1 else "Unmarried", dependents=dependents, education="Graduate" if education == 1 else "Ungraduated", self_employed="Yes" if self_employed == 1 else "No", applicant_income=applicant_income, coapplicant_income=coapplicant_income, loan_amount=loan_amount, credit_history=credit_history, property_area=property_area_text);
        else:
            result = "Not Approved"

        #return render_template('result.html', prediction=result)

# Function to fetch credit history for a given email
def fetch_credit_history(email):
    # Read user data from JSON file
    with open('user_data.json', 'r') as json_file:
        user_data = json.load(json_file)

    # Check if the user's email exists in the data
    if email in user_data:
        # Extract credit history for the user
        return user_data[email].get('credit_history')
    else:
        return None

# Route to fetch credit history
@app.route('/execute_script')
def execute_script():
    # Fetch email from the session
    email = session.get('email')
    if not email:
        # If email not found in session, fetch from request parameters
        email = request.args.get('email')

    if email:
        # Fetch credit history for the logged-in user
        credit_history = fetch_credit_history(email)
        if credit_history is not None:
            # Debugging: Print credit history to console
            print("Credit History Retrieved:", credit_history);
            return render_template('input.html', credit_history=credit_history)
        else:
            return "Credit history not found for this user."
    else:
        return "Email not provided."

if __name__ == '__main__':
    app.run(debug=True)




