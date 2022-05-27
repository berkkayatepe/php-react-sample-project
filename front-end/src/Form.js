import React from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import IntlTelInput from 'react-intl-tel-input';
import 'react-intl-tel-input/dist/main.css';
import axios from 'axios';
import {domain} from './config';



class Form extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            name: '',
            email: '',
            address: '',
            phone: '',
            buttonDisabled: true,
            responseStatus: '',
            responseMessage: ''
        };
        this.handleChange = this.handleInputChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handlePhoneChange = (status, phoneNumber, country) => {
        if (status) this.setState({buttonDisabled: false})
        else this.setState({buttonDisabled: true})
        this.setState({phoneNumber, status, country, phone: (country.dialCode + phoneNumber)});
    };

    handleInputChange(event) {
        const {name, value} = event.target;
        this.setState({
            [name]: value
        });
    }

    handleSubmit(event) {
        event.preventDefault();
        const full_name = this.state.name;
        const email = this.state.email;
        const phone = this.state.phone;
        const address = this.state.address;
        const data = {
            full_name: full_name,
            email: email,
            phone: phone,
            address: address,
        };
        axios.post(domain+'/backend/api/createCustomer', data)
            .then(response => this.setState({
                responseStatus: response.data.status,
                responseMessage: response.data.message
            }));
    }

    render() {
        var messageClass = this.state.responseStatus ? 'alert alert-success mt-5' : 'alert alert-danger mt-5';
        return (
            <div className="container pt-5">
                <div className="row">

                    <form onSubmit={this.handleSubmit} className="col-md-6 col-12 mx-auto">
                        <div className="col-12 mb-4 ">
                            <a href="/list" className="btn btn-primary float-end"> List </a>
                        </div>
                        {this.state.responseMessage
                        &&
                        <div className={messageClass} role="alert">
                            {this.state.responseMessage}
                        </div>
                        }

                        <div className="form-group mb-2">
                            <label for="nameImput">Full Name</label>
                            <input type="text" name="name" value={this.state.name} onChange={this.handleChange}
                                   className="form-control" id="nameImput" placeholder="Name"/>
                        </div>
                        <div className="form-group mb-2">
                            <label for="emailImput">E-mail</label>
                            <input name="email" type="email" value={this.state.email} onChange={this.handleChange}
                                   className="form-control" id="emailImput" placeholder="email@domain.com"/>
                        </div>
                        <div className="form-group mb-2">
                            <label htmlFor="phoneImput">Phone</label>
                            <div className="clearfix"></div>
                            <IntlTelInput
                                containerClassName="intl-tel-input  w-100"
                                inputClassName="form-control"
                                input
                                type="tel"
                                preferredCountries={['tr']}
                                value={this.state.phoneNumber}
                                onPhoneNumberChange={this.handlePhoneChange}
                            />
                        </div>
                        <div className="form-group mb-2">
                            <label htmlFor="phoneImput">Address</label>
                            <textarea rows="5" className="form-control" name="address" value={this.state.address}
                                      onChange={this.handleChange}> </textarea>
                        </div>
                        <input type="submit" value="Add Customer" disabled={this.state.buttonDisabled}
                               className="btn btn-primary"/>
                    </form>
                </div>
            </div>
        )
    }
}

export default Form;