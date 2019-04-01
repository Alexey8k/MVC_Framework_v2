import React, {Component} from 'react';

class OrderForm extends Component {

    state = {
        quantity: 1
    };

    render() {
        const {quantity} = this.state;
        return (
            <div className={'panel panel-default'}>
                <div className={'panel-heading'}>
                    <h3 className={'panel-title'}>{'Order details'}</h3>
                </div>
                <div className={'panel-body'}>
                    <form method={'POST'} onSubmit={this.submitOrder} className={'form-horizontal'}>
                        <div className={'form-group'}>
                            <label htmlFor={'userName'} className={'col-sm-3 control-label'}>{'Name'}</label>
                            <div className={'col-sm-9'}>
                                <input type={'text'} className={'form-control'} id={'userName'} name={'userName'}
                                       placeholder={'Name'} onKeyPress={this.preventSubmissionEnterKey} />
                            </div>
                        </div>
                        <div className={'form-group'}>
                            <label htmlFor={'userAddress'} className={'col-sm-3 control-label'}>{'Address'}</label>
                            <div className={'col-sm-9'}>
                                <input type={'text'} className={'form-control'} id={'userAddress'} name={'userAddress'}
                                       placeholder={'Address'} onKeyPress={this.preventSubmissionEnterKey} />
                            </div>
                        </div>
                        <div className={'form-group'}>
                            <label htmlFor={'quantity'} className={'col-sm-3 control-label'}>{'Quantity'}</label>
                            <div className={'cart-amount col-sm-9'}>
                                <button type={'button'} name={'minus'} className={'cart-amount-qnt-btn'} onClick={this.onChangeQuantity} ><span>-</span></button>
                                <input type={'text'} name={'quantity'} id={'quantity'} className={'cart-amount-input-text'}
                                       value={quantity} onChange={this.onChangeQuantity} onKeyPress={this.preventSubmissionEnterKey} />
                                <button type={'button'} name={'plus'} className={'cart-amount-qnt-btn'} onClick={this.onChangeQuantity} ><span>+</span></button>
                            </div>
                        </div>
                        <div className={'form-group'}>
                            <div className={'col-sm-offset-3 col-sm-9'}>
                                <button type={'submit'} className={'btn btn-sm btn-primary'}>{'Order'}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        )
    }

    preventSubmissionEnterKey = event => {
        if (event.which === 13)
            event.preventDefault();
        return !(event.which === 13);
    };

    submitOrder = (event) => {
        event.preventDefault();

        let orderData = {
            bookId: this.props.bookId,
            quantity: this.state.quantity,
            userName: event.currentTarget.userName.value,
            userAddress: event.currentTarget.userAddress.value
        };

        $.ajax({
                url: 'order',
                method: 'POST',
                data: orderData,
                cache: false,
                success: (response) => {
                    this.props.onCloseDialog();
                },
                error: function (err) {
                    console.log("Ошибка подключения к серверу, код ошибки: " + err);
                }
            }
        );
    };

    onChangeQuantity = (event) => {
        const updateQuantity = (newQuantity) => {
            if (newQuantity < 1)
                return;
            this.setState({ quantity: newQuantity });
        };

        switch (event.currentTarget.name) {
            case "plus":
                updateQuantity(this.state.quantity + 1);
                break;
            case "minus":
                updateQuantity(this.state.quantity - 1);
                break;
            case "quantity":
                const quantity = parseInt(event.currentTarget.value);
                updateQuantity(quantity);
                break;
        }
    };

}

export default OrderForm;