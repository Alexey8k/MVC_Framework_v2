import React, {Component} from 'react';
import BookDetails from './BookDetails'
import OrderForm from "./OrderForm";

class Dialog extends Component {
    state = {
        isShowOrderForm: false,
    };

    render() {
        const orderForm = this.state.isShowOrderForm
            ? <OrderForm bookId={this.props.bookId} onCloseDialog={this.onCloseDialog} />
            : null;

        return (
            <div ref={element => this.element = element}>
                <BookDetails bookId={this.props.bookId} showOrderForm={this.showOrderForm} />
                { orderForm }
            </div>
        )
    }

    componentDidMount() {
        this.initDialog();
    }

    componentWillUnmount() {
        this.$element.dialog("destroy");
    }

    onCloseDialog = () => {
        this.$element.dialog("close");
        this.props.onCloseBookDetails();
    };

    showOrderForm = () => this.setState({ isShowOrderForm: true });

    initDialog() {
        this.$element = $(this.element);

        this.$element.dialog({
            autoOpen: false,
            height: "auto",
            width: 700,
            position: { my: "center top", at: "center top", of: window },
            //maxHeight: 350,
            modal: true,
            resizable: false,
            close: this.props.onCloseBookDetails
        }).dialog("open");
    }
}

export default Dialog