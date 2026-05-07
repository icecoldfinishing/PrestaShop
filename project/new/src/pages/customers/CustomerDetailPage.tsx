import { Link, useParams } from "react-router-dom";
import { useCustomerDetail } from "../../hooks/customers/useCustomers";

export default function CustomerDetailPage() {
    const params = useParams();
    const customerId = Number(params.customerId);

    const isValidId = Number.isFinite(customerId) && customerId > 0;

    const { data: customer, loading, error } =
        useCustomerDetail(isValidId ? customerId : null);

    if (!isValidId) {
        return (
            <div className="alert alert-warning mt-4">
                ID client invalide.
            </div>
        );
    }

    return (
        <section className="w-100">

            <nav aria-label="breadcrumb" className="mb-3">
                <ol className="breadcrumb">
                    <li className="breadcrumb-item"><Link to="/">Accueil</Link></li>
                    <li className="breadcrumb-item"><Link to="/customers">Clients</Link></li>
                    <li className="breadcrumb-item active">
                        {customer ? `${customer.firstName} ${customer.lastName}` : "Chargement..."}
                    </li>
                </ol>
            </nav>

            <Link to="/customers" className="btn btn-outline-secondary btn-sm mb-4">
                Retour à la liste
            </Link>

            {loading ? (
                <div className="text-center py-5">
                    <div className="spinner-border text-primary" />
                </div>
            ) : null}

            {error ? (
                <div className="alert alert-danger">{error}</div>
            ) : null}

            {customer ? (
                <div className="card shadow-sm border-0 p-4">
                    <h1 className="fw-bold mb-2">
                        {customer.firstName} {customer.lastName}
                    </h1>

                    <p className="text-muted mb-2">
                        Email : {customer.email}
                    </p>

                    <p className="mb-2">
                        ID : {customer.id}
                    </p>

                    <span className={`badge ${customer.active ? "bg-success" : "bg-secondary"}`}>
                        {customer.active ? "Actif" : "Inactif"}
                    </span>

                    <hr />

                    <p className="text-muted small">
                        Créé le : {customer.dateAdd}
                    </p>

                    <p className="text-muted small">
                        Mis à jour : {customer.dateUpd}
                    </p>
                </div>
            ) : null}
        </section>
    );
}